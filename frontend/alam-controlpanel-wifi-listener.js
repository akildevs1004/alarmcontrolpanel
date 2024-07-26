const net = require("net");
const fs = require("fs");
const axios = require("axios");

const port = 2503;
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

  const regexEvent =
    /([a-zA-Z0-9]{8})"ADM-CID"\d{4}(R\d{4}L\d{4})#\d+\[#\d+\|([a-zA-Z0-9]{4}) \d{2} \d{3}\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;
  const regexHeartbeat =
    /([a-zA-Z0-9]{8})"NULL"\d{4}(R\d{4}L\d{4})#\d+\[\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;

  const matchEvent = message.match(regexEvent);
  const matchHeartbeat = message.match(regexHeartbeat);

  if (matchEvent || matchHeartbeat) {
    const match = matchEvent || matchHeartbeat;
    const isHeartbeat = !!matchHeartbeat;

    const recordNumber = match[1];
    const deviceId = match[2];
    const eventCode = isHeartbeat ? "HEARTBEAT" : match[3];
    const time = isHeartbeat ? match[3] : match[4];
    const day = isHeartbeat ? match[4] : match[5];
    const month = isHeartbeat ? match[5] : match[6];
    const year = isHeartbeat ? match[6] : match[7];
    const timestamp = `${year}-${month}-${day} ${time}`;

    const logEntry = `${deviceId},${eventCode},${timestamp},${recordNumber}`;
    fs.appendFileSync(logFilePath, logEntry + "\n");
    console.log(logEntry);
    await sendToBackend(timestamp);
  }
}
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
