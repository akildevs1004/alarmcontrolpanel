<template>
  <div>
    <h1>HLS Video Player</h1>
    <VideoPlayerComponent src="https://rtmp.oxsai.com/hls/cameralive.m3u8" />
  </div>
</template>

<script>
import VideoPlayerComponent from "../../components/Alarm/VideoPlayerComponent.vue";

export default {
  components: {
    VideoPlayerComponent,
  },
};
</script>
