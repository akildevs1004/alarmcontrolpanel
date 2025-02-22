const net = require("net");
const fs = require("fs");
const axios = require("axios");

const port = 2504;
let isAPIConnected = false;
const server = net.createServer((socket) => {
  console.log("Client connected");

  //fs.appendFileSync(logRawDataFilePath, "");

  // log(`Device    : Client connected`);

  socket.on("data", (data) => {
    let todayDatetime = getTime();

    const decodedData = data.toString("utf8").trim();
    //log(`Received data in UTF-8: ${decodedData}`);
    log(`${decodedData}`);
    parseMessage(decodedData);
  });

  socket.on("end", () => {
    //log("Client disconnected");
    //fs.appendFileSync(logRawDataFilePath, "\nClient disconnected");
  });

  socket.on("error", (error) => {
    // log(`Socket Error: ${error.message}`);
  });
});

server.on("error", (error) => {
  //log(`Server Error: ${error.message}`);
});

server.listen(port, () => {
  log(`TCP socket server is running on port: ${port}`);
  // //let decodedData =
  // ('F92F0043"ADM-CID"0020R7896L7896#3456[#3456|1407 00 001]_17:01:14,07-26-2024');
  // let decodedData = 'D730002F"NULL"0000R7896L7896#3456[]_17:01:26,07-26-2024';
  // parseMessage(decodedData);
});

function log(message) {
  const logRawDataFilePath = `../backend/storage/app/alarm-sensors/raw-sensor-${
    getFormattedDate().date
  }.txt`;
  let todayDatetime = getTime();
  console.log(`${todayDatetime} - ${message}`);
  fs.appendFileSync(logRawDataFilePath, `\n${message}`);
}

function getTime() {
  let date_ob = new Date();
  date_ob.setHours(date_ob.getHours() + 4);
  let date = ("0" + date_ob.getDate()).slice(-2);
  let month = ("0" + (date_ob.getMonth() + 1)).slice(-2);
  let year = date_ob.getFullYear();
  let hours = date_ob.getHours();
  let minutes = ("0" + date_ob.getMinutes()).slice(-2);
  let seconds = ("0" + date_ob.getSeconds()).slice(-2);
  return `${year}-${month}-${date} ${hours}:${minutes}:${seconds}`;
}

async function parseMessage(message) {
  log(message);
  const logFilePath = `../backend/storage/app/alarm-sensors/sensor-logs-${
    getFormattedDate().date
  }.csv`;
  // 299D002F"NULL"0000R7896L7896#3456[]_17:57:42,07-30-2024
  // ED360043"ADM-CID"0022R7896L7896#3456[#3456|3401 00 000]_18:00:05,07-30-2024
  // 3FA70043"ADM-CID"0008R7896L7896#3456[#3456|3407 00 001]_14:11:31,07-30-2024
  // 282A0043"ADM-CID"0009R7896L7896#3456[#3456|1c45 00 003]_14:11:43,07-30-2024

  // message =
  //   '282A0043"ADM-CID"0009R7896L7896#3456[#3456|1c45 00 003]_14:11:43,07-30-2024';
  // message = '299D002F"NULL"0000R7896L7896#3456[]_17:57:42,07-30-2024';
  // const regexEvent =
  //   /([a-zA-Z0-9]{8})"ADM-CID"\d{4}(R\d{4}L\d{4})#(\d+)\[#(\d+)\|([a-zA-Z0-9]{4}) (\d{2}) (\d{3})\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;

  const regexEvent =
    /([a-zA-Z0-9]{8})"ADM-CID"\d{4}(R\d{1}L\d{1})#([a-zA-Z0-9]+)\[#([a-zA-Z0-9]+)\|(\d{4}) (\d{2}) (\d{3})\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;

  // const regexHeartbeat =
  //   /([a-zA-Z0-9]{8})"NULL"(\d{4})(R\d{4}L\d{4})#(\d{4})\[\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;
  const regexHeartbeat =
    /([a-zA-Z0-9]{8})"NULL"(\d{4})(R\d{1}L\d{1})#([a-zA-Z0-9]+)\[\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;

  const matchEvent = message.match(regexEvent);
  const matchHeartbeat = message.match(regexHeartbeat);

  if (matchEvent || matchHeartbeat) {
    const match = matchEvent || matchHeartbeat;

    //console.log(match);
    const isHeartbeat = !!matchHeartbeat;

    const deviceId = match[4]; // Extracted device code
    const zone = isHeartbeat ? "" : match[7]; // Extracted from the pattern
    const area = isHeartbeat ? "" : match[6]; // Extracted from the pattern
    const eventCode = isHeartbeat ? "HEARTBEAT" : match[5];
    const time = isHeartbeat ? match[5] : match[8];
    const day = isHeartbeat ? match[7] : match[10];
    const month = isHeartbeat ? match[6] : match[9];
    const year = isHeartbeat ? match[8] : match[11];
    const timestamp = `${year}-${month}-${day} ${time}`;

    const logEntry = `${deviceId},${eventCode},${timestamp},RECORDNUMBER,${area},${zone}`;
    fs.appendFileSync(logFilePath, logEntry + "\n");
    console.log("Event Found ", eventCode);
    //await sendToBackend(timestamp);
  } else {
    console.log("logEntry Not Matched");
  }
}
// async function parseMessage_working(message) {
//   log(message);
//   const logFilePath = `../backend/storage/app/alarm-sensors/sensor-logs-${
//     getFormattedDate().date
//   }.csv`;
//   // 299D002F"NULL"0000R7896L7896#3456[]_17:57:42,07-30-2024
//   // ED360043"ADM-CID"0022R7896L7896#3456[#3456|3401 00 000]_18:00:05,07-30-2024
//   // 3FA70043"ADM-CID"0008R7896L7896#3456[#3456|3407 00 001]_14:11:31,07-30-2024
//   // 282A0043"ADM-CID"0009R7896L7896#3456[#3456|1c45 00 003]_14:11:43,07-30-2024

//   //message =
//   //('282A0043"ADM-CID"0009R7896L7896#3456[#3456|1c45 00 003]_14:11:43,07-30-2024');
//   const regexEvent =
//     /([a-zA-Z0-9]{8})"ADM-CID"\d{4}(R\d{4}L\d{4})#\d+\[#(\d+)\|([a-zA-Z0-9]{4}) \d{2} \d{3}\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;

//   const regexHeartbeat =
//     /([a-zA-Z0-9]{8})"NULL"(\d{4})(R\d{4}L\d{4})#(\d{4})\[\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;

//   const matchEvent = message.match(regexEvent);
//   const matchHeartbeat = message.match(regexHeartbeat);

//   if (matchEvent || matchHeartbeat) {
//     const match = matchEvent || matchHeartbeat;

//     console.log(match);
//     const isHeartbeat = !!matchHeartbeat;

//     const recordNumber = match[3];
//     const deviceId = isHeartbeat ? match[4] : match[3];
//     const eventCode = isHeartbeat ? "HEARTBEAT" : match[4];
//     const time = isHeartbeat ? match[5] : match[5];
//     const day = isHeartbeat ? match[7] : match[7];
//     const month = isHeartbeat ? match[6] : match[6];
//     const year = isHeartbeat ? match[8] : match[8];
//     const timestamp = `${year}-${month}-${day} ${time}`;

//     const logEntry = `${deviceId},${eventCode},${timestamp},${recordNumber}`;
//     fs.appendFileSync(logFilePath, logEntry + "\n");
//     console.log(logEntry);
//     await sendToBackend(timestamp);
//   }
// }

// async function parseMessage_old(message) {
//   log(message);
//   const logFilePath = `../backend/storage/app/alarm-sensors/sensor-logs-${
//     getFormattedDate().date
//   }.csv`;

//   const regexEvent =
//     /([a-zA-Z0-9]{8})"ADM-CID"\d{4}(R\d{4}L\d{4})#\d+\[#\d+\|([a-zA-Z0-9]{4}) \d{2} \d{3}\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;
//   const regexHeartbeat =
//     /([a-zA-Z0-9]{8})"NULL"\d{4}(R\d{4}L\d{4})#\d+\[\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;

//   const matchEvent = message.match(regexEvent);
//   const matchHeartbeat = message.match(regexHeartbeat);

//   if (matchEvent || matchHeartbeat) {
//     const match = matchEvent || matchHeartbeat;
//     const isHeartbeat = !!matchHeartbeat;

//     const recordNumber = match[1];
//     const deviceId = match[2];
//     const eventCode = isHeartbeat ? "HEARTBEAT" : match[3];
//     const time = isHeartbeat ? match[3] : match[4];
//     const day = isHeartbeat ? match[5] : match[6];
//     const month = isHeartbeat ? match[4] : match[5];
//     const year = isHeartbeat ? match[6] : match[7];
//     const timestamp = `${year}-${month}-${day} ${time}`;

//     const logEntry = `${deviceId},${eventCode},${timestamp},${recordNumber}`;
//     fs.appendFileSync(logFilePath, logEntry + "\n");
//     console.log(logEntry);
//     await sendToBackend(timestamp);
//   } else {
//     console.log("Pattern not found");
//   }
// }
async function sendToBackend(timestamp) {
  const params = { timestamp };
  if (!isAPIConnected) {
    isAPIConnected = true;
    const url = "https://alarmbackend.xtremeguard.org/api/read_csv_file";
    try {
      const response = await axios.get(url, {
        params,
        timeout: 1000 * 30, // 30 seconds timeout
      });
      // console.log("Response from backend:", response);
    } catch (error) {
      // console.error("Error getting from backend:", error.message);
    } finally {
      isAPIConnected = false;
    }
  }
}
function getFormattedDate() {
  const options = {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: false, // Use 24-hour format
    // timeZone: "Asia/Dubai",
  };
  const [newDate, newTime] = new Intl.DateTimeFormat("en-US", options)
    .format(new Date())
    .split(",");
  const [m, d, y] = newDate.split("/");

  return {
    date: `${d.padStart(2, 0)}-${m.padStart(2, 0)}-${y}`,
    time: newTime,
  };
}
