<template>
  <footer class="footer">
    <FooterIcon type="home" :activeButton="activeButton" />
    <FooterIcon type="search" :activeButton="activeButton" />
    <FooterIcon
      v-if="isAuthorized"
      type="bookshelf"
      :activeButton="activeButton"
    />
  </footer>
</template>

<script>
import FooterIcon from "~/components/icon/FooterIcon.vue";

export default {
  components: { FooterIcon },
  props: {
    isAuthorized: {
      type: Boolean,
      default: () => false,
    },
    activeButton: {
      type: String,
      default: () => "",
    },
  },
};
</script>

<style lang="scss" scoped>
.footer {
  display: flex;
  justify-content: space-around;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: var(--secondary-color);
}
</style>