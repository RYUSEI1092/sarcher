<template>
  <teleport to="body">
    <div v-if="isShow" class="overlay" @click="onClickOverlay">
      <div class="modal__container" @click.stop="() => {}">
        <slot></slot>
      </div>
    </div>
  </teleport>
</template>

<script>
import {
  disableBodyScroll,
  enableBodyScroll,
  clearAllBodyScrollLocks,
} from "body-scroll-lock";

export default {
  props: {
    isShow: {
      type: Boolean,
      default: () => false,
    },
  },
  data() {
    return {
      window: null,
    };
  },
  methods: {
    open() {
      this.modal = document.querySelector("body");
      disableBodyScroll(this.modal);
    },
    close() {
      enableBodyScroll(this.modal);
      clearAllBodyScrollLocks();
    },
    onClickOverlay() {
      this.close();
      this.$emit("close");
    },
  },
  watch: {
    isShow(isShow) {
      if (isShow) {
        this.open();
      } else {
        this.close();
      }
    },
  },
};
</script>

<style scoped lang="scss">
.overlay {
  z-index: 10;
  position: fixed;
  display: flex;
  justify-content: center;
  align-items: center;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.7);
}

.modal__container {
  margin: auto;
  max-width: 80vw;
  max-height: 80vh;
}
</style>