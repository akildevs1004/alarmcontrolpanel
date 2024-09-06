<template></template>
<script>
export default {
  layout: "login",
  created() {
    this.$axios.get(`/logout`).then(({ res }) => {
      this.$auth.logout();
      this.$router.push(`/login`);
    });

    // windows.location.href = "/login";
    setTimeout(() => {
      try {
        if (localStorage) {
          localStorage.setItem("auth._token.local", false);
          localStorage.setItem("auth._token_expiration.local", false);

          return false;
        }
      } catch (e) {}
      this.$router.push(`/login`);
    }, 1000);
  },
};
</script>
