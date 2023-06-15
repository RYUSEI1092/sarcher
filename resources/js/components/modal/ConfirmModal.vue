<template>
  <ModalBase :is-show="isShow" @close="$emit('close')">
    <div class="modal__container">
      <div class="box message">
        <span>{{ message }}</span>
      </div>
      <div class="buttons">
        <template v-if="centerMessage">
          <button class="box button" @click.stop="$emit('click-center')">
            <img v-if="centerIcon" :src="centerIconUrl" />
            <span>{{ centerMessage }}</span>
          </button>
        </template>
        <template v-else>
          <button class="box button" @click.stop="$emit('click-left')">
            <img v-if="leftIcon" :src="leftIconUrl" />
            <span>{{ leftMessage }}</span>
          </button>
          <button class="box button" @click.stop="$emit('click-right')">
            <img v-if="rightIcon" :src="rightIconUrl" />
            <span>{{ rightMessage }}</span>
          </button>
        </template>
      </div>
    </div>
  </ModalBase>
</template>

<script>
import ModalBase from "~/components/modal/ModalBase.vue";

export default {
  components: {
    ModalBase,
  },
  props: {
    isShow: {
      type: Boolean,
      default: () => false,
    },
    message: {
      type: String,
      default: () => "message",
    },
    centerMessage: {
      type: String,
      default: () => "",
    },
    centerIcon: {
      type: String,
      default: () => "",
    },
    leftMessage: {
      type: String,
      default: () => "leftMessage",
    },
    leftIcon: {
      type: String,
      default: () => "",
    },
    rightMessage: {
      type: String,
      default: () => "rightMessage",
    },
    rightIcon: {
      type: String,
      default: () => "",
    },
  },
  computed: {
    centerIconUrl() {
      return this.getIconUrl(this.centerIcon);
    },
    leftIconUrl() {
      return this.getIconUrl(this.leftIcon);
    },
    rightIconUrl() {
      return this.getIconUrl(this.rightIcon);
    },
  },
  methods: {
    getIconUrl(iconName) {
      return new URL(`../../../images/icons/${iconName}.svg`, import.meta.url)
        .href;
    },
  },
};
</script>

<style scoped lang="scss">
.modal__container {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.box {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: var(--secondary-color);
  box-shadow: 0px 4px 4px rgb(0 0 0 / 25%);
}

.message {
  padding: 16px;
  width: 80vw;
  min-width: 256px;
  min-height: 256px;
  font-size: var(--font-xl);
}

.buttons {
  display: flex;
  gap: 16px;
}

.button {
  padding: 13px;
  width: 100%;
  font-size: var(--font-xl);
  display: flex;
  gap: 4px;
}
</style>