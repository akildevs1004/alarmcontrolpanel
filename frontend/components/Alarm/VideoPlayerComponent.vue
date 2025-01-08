<template>
  <div class="video-container">
    <video
      ref="videoPlayer"
      class="video-js vjs-default-skin"
      controls
      preload="auto"
      width="640"
      height="360"
      data-setup="{}"
    ></video>
  </div>
</template>

<script>
import videojs from "video.js";
import "video.js/dist/video-js.css";

export default {
  props: {
    src: {
      type: String,
      required: true,
    },
  },
  mounted() {
    this.player = videojs(this.$refs.videoPlayer, {
      autoplay: true,
      controls: true,
      preload: "auto",
      sources: [
        {
          src: this.src,
          type: "application/x-mpegURL",
        },
      ],
    });
  },
  beforeDestroy() {
    if (this.player) {
      this.player.dispose();
    }
  },
};
</script>

<style>
.video-container {
  margin: auto;
  max-width: 100%;
}
</style>
