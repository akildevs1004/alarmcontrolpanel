const net = require("net");
const fs = require("fs");

const port = 2503;

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

function parseMessage(message) {
  log(`${message}`);
  const logFilePath = `../backend/storage/app/alarm-sensors/sensor-logs-${
    getFormattedDate().date
  }.csv`;

  //D2A70043"ADM-CID"0006R7896L7896#3456[#3456|3401 00 000]_11:22:55,07-25-2024
  //7D440043"ADM-CID"0018R7896L7896#3456[#3456|1b12 00 001]_11:56:03,07-25-2024

  const regex =
    /([a-zA-Z0-9]{8})"ADM-CID"\d{4}(R\d{4}L\d{4})#\d+\[#\d+\|([a-zA-Z0-9]{4}) \d{2} \d{3}\]_(\d{2}:\d{2}:\d{2}),(\d{2})-(\d{2})-(\d{4})/;
  const match = message.match(regex);
  console.log(match);
  if (match) {
    const recordNumber = match[1];
    const deviceId = match[2];
    const eventCode = match[3];
    const time = match[4];
    const month = match[5];
    const day = match[6];
    const year = match[7];
    const timestamp = `${year}-${month}-${day} ${time}`;

    const logEntry = `${deviceId},${eventCode},${timestamp},${recordNumber}`;
    fs.appendFileSync(logFilePath, logEntry + "\n");
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
