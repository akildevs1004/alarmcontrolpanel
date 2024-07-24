const net = require("net");
const fs = require("fs");

const port = 2503;

const server = net.createServer((socket) => {
  console.log("Client connected");
  fs.appendFileSync("message.txt", "");

  log(`Device    : Client connected`);
  fs.appendFileSync("message.txt", `\nDevice    : Client connected`);

  socket.on("data", (data) => {
    let todayDatetime = getTime();

    const decodedData = data.toString("utf8").trim();
    log(`Received data in UTF-8: ${decodedData}`);
    parseMessage(decodedData);
  });

  socket.on("end", () => {
    log("Client disconnected");
    fs.appendFileSync("message.txt", "\nClient disconnected");
  });

  socket.on("error", (error) => {
    log(`Socket Error: ${error.message}`);
  });
});

server.on("error", (error) => {
  log(`Server Error: ${error.message}`);
});

server.listen(port, () => {
  log(`TCP socket server is running on port: ${port}`);
});

function log(message) {
  let todayDatetime = getTime();
  console.log(`${todayDatetime} - ${message}`);
  fs.appendFileSync("message.txt", `\n${todayDatetime} - ${message}`);
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
  const regex =
    /"ADM-CID"\d{4}(R\d{4}L\d{4})#\d+\[#\d+\|([0-9a-f]{4}) \d{2} \d{3}\]_(\d{2}:\d{2}:\d{2},\d{2}-\d{2}-\d{4})/i;
  const match = message.match(regex);

  if (match) {
    const deviceId = match[1];
    const eventCode = match[2];
    const timestamp = match[3];

    log(
      `Parsed Message: Device ID: ${deviceId}, Event Code: ${eventCode}, Timestamp: ${timestamp}`
    );

    if (eventCode === "1407") {
      log(`Event: Disarm button pressed by device ${deviceId}`);
    } else if (eventCode === "3407") {
      log(`Event: Armed button pressed by device ${deviceId}`);
    } else if (eventCode === "1b12") {
      log(`Event: Alarm sensor triggered by device ${deviceId}`);
    } else {
      log(`Event: Unknown event code ${eventCode} from device ${deviceId}`);
    }
  } else {
    log(`Failed to parse message: ${message}`);
  }
}
