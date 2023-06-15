<template>
  <section class="login__container">
    <h1 class="login__header">ログイン</h1>
    <form action="/login" method="post" class="login__form">
      <input type="hidden" name="_token" :value="csrf" />
      <div class="login__section">
        <span class="login__title">ID</span>
        <label class="login__label" for="mail">
          <FormInput
            v-model="mail"
            id="mail"
            placeholder="example@xxx.com"
            required
            autofocus
          />
        </label>
      </div>

      <div class="login__section">
        <span class="login__title">パスワード</span>
        <label class="login__label" for="password">
          <FormInput
            v-model="password"
            id="password"
            type="password"
            required
            minlength="8"
            maxlength="32"
          />
          <InputErrorLabel class="login__errors" :errors="errors" id="mail" />
        </label>
      </div>

      <div class="login__footer">
        <SubmitButton size="md">ログイン</SubmitButton>
        <a class="login__link" href="/register">新規登録はこちら</a>
      </div>
    </form>
  </section>
</template>

<script>
import FormInput from "~/components/input/FormInput.vue";
import InputErrorLabel from "~/components/label/InputErrorLabel.vue";
import SubmitButton from "~/components/button/SubmitButton.vue";

export default {
  components: {
    FormInput,
    InputErrorLabel,
    SubmitButton,
  },
  props: {
    csrf: {
      type: String,
      default: () => "",
    },
    old: {
      type: Object,
      default: () => ({}),
    },
    errors: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      mail: "",
      password: "",
    };
  },
  created() {
    this.mail = this.old["mail"] ?? "";
  },
};
</script>

<style scoped lang="scss">
.login {
  &__container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 64px;
  }

  &__header {
    font-size: var(--font-xl);
    font-weight: bold;
    margin-bottom: 32px;
  }

  &__form {
    display: flex;
    flex-direction: column;
    gap: 24px;
    width: 100%;
  }

  &__title {
    font-size: var(--font-lg);
  }

  &__label {
    width: 100%;
    display: flex;
    flex-direction: column;
  }

  &__errors {
    margin-bottom: 8px;
  }

  &__footer {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  &__link {
    text-align: center;
    font-size: var(--font-md);
  }
}
</style>
