const WebSocket = require("ws");
const fs = require("fs");
require("dotenv").config();

const options = {
  year: "numeric",
  month: "2-digit",
  day: "2-digit",
  hour: "2-digit",
  minute: "2-digit",
  second: "2-digit",
  hour12: false, // Use 24-hour format
  timeZone: "Asia/Dubai",
};

const [newDate, newTime] = new Intl.DateTimeFormat("en-US", options)
  .format(new Date())
  .split(",");
const [m, d, y] = newDate.split("/");
const formattedDate = `${d.padStart(2, 0)}-${m.padStart(2, 0)}-${y}`;

console.log(`Current Date: ${formattedDate}`);
console.log(`Current Time: ${newTime.trim()}`);

const { SOCKET_ENDPOINT } = "ws://192.168.2.17:8888/api/websocket";
//process.env;
//"ws://192.168.2.35:8888/api/websocket"
//ws://192.168.2.17:8888/api/websocket

// Create a WebSocket connection
const socket = new WebSocket("ws://192.168.2.17:8888/api/websocket");

// Handle WebSocket connection events
socket.onopen = () => {
  console.log(`Connected to ${SOCKET_ENDPOINT}`);
};

socket.onerror = (error) => {
  console.error("WebSocket error:", error.message);
};
// Handle WebSocket close event
socket.onclose = (event) => {
  console.error(
    `WebSocket connection closed with code ${
      event.code
    } at ${formattedDate} ${newTime.trim()}`
  );
};

socket.onmessage = ({ data }) => {
  console.log(data);
};

process.on("SIGTERM", () => {
  console.log(`Prcess killed at ${formattedDate} ${newTime.trim()}`);
  process.exit(0); // Exit the process gracefully
});

process.on("SIGINT", () => {
  console.log(`Prcess killed at ${formattedDate} ${newTime.trim()}`);
  process.exit(0); // Exit the process gracefully
});
