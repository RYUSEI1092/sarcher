<template>
  <ModalBase :is-show="isShow" @close="$emit('close')">
    <div class="modal__container">
      <div class="box message">
        <span class="title">返却確認</span>
        <div class="book-title__container">
          <span v-for="title in titles" :key="title" class="book-title">{{ title }}</span>
        </div>
        <span class="books-count">{{ titles.length }}/{{ booksCount }}</span>
      </div>
      <div class="buttons">
        <button class="box button" @click.stop="$emit('click-left')">
          <img v-if="leftIcon" :src="leftIconUrl" />
          <span>{{ leftMessage }}</span>
        </button>
        <button class="box button" @click.stop="$emit('click-right')">
          <img v-if="rightIcon" :src="rightIconUrl" />
          <span>{{ rightMessage }}</span>
        </button>
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
    titles: {
      type: Array,
      default: () => [],
    },
    booksCount: {
      type: Number,
      default: 0,
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
  emits: ["close", "click-left", "click-right"],
  computed: {
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

.title {
  font-weight: bold;
}

.book-title__container {
  width: 100%;
  display: flex;
  flex-direction: column;
  max-height: 300px;
  overflow-y: scroll;
  border: solid 1px;
  padding: 8px;
  flex: 1;
}

.book-title {
  font-size: var(--font-md);
}

.books-count {
  font-size: var(--font-lg);
  margin-top: 6px;
}

.message {
  padding: 16px;
  width: 80vw;
  min-width: 256px;
  min-height: 256px;
  font-size: var(--font-xl);
  flex-direction: column;
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