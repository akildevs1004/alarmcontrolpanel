const net = require("net");

const server = net.createServer((socket) => {
  console.log("Client connected");

  // Handle data received from the client
  socket.on("data", (data) => {
    console.log(`Received data: ${data}`);
  });

  // Handle client disconnection
  socket.on("end", () => {
    console.log("Client disconnected");
  });
});

// Set the server to listen on the specified IP address and port
const ipAddress = "192.168.2.17:8888/api/websocket";
const port = 8888;

server.listen(port, ipAddress, () => {
  console.log(`Server listening on ${ipAddress}:${port}`);
});
