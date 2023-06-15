<template>
  <div class="error-container">
    <span class="error-container__reason">{{ reason }}</span>
    <span
      v-for="(line, index) in message"
      :key="index"
      class="error-container__message"
      >{{ line }}</span
    >

    <form action="/" method="get" class="error-container__button">
      <SubmitButton size="md">メインページへ</SubmitButton>
    </form>
  </div>
</template>

<script>
import SubmitButton from "~/components/button/SubmitButton.vue";

export default {
  components: { SubmitButton },
  props: {
    errorCode: {
      type: Number,
      default: () => 500,
    },
  },
  computed: {
    reason() {
      const reasons = {
        404: "Not Found",
        500: "Internal Server Error",
      };

      return `${this.errorCode} ${reasons[this.errorCode]}`;
    },

    message() {
      const messages = {
        404: [
          "ページが見つかりませんでした",
          "アクセスしたファイルが削除されているか",
          "URLが間違っている可能性があります",
        ],
        500: [
          "予期せぬエラーが発生しました",
          "このサイトはメンテナンス中であるか",
          "プログラミングエラーが発生している可能性があります",
        ],
      };

      return messages[this.errorCode];
    },
  },
};
</script>

<style lang="scss" scoped>
.error-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;

  &__reason {
    font-size: var(--font-xl);
    font-weight: bold;
    margin-bottom: 24px;
  }

  &__message {
    font-size: var(--font-md);
    margin-bottom: 8px;
  }

  &__button {
    margin-top: 20px;
  }
}
</style>