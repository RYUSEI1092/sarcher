<template>
  <section class="register__container">
    <h1 class="register__header">お客様情報の入力</h1>
    <form action="/register" method="post" class="register__form">
      <input type="hidden" name="_token" :value="csrf" />
      <div class="register__section">
        <RequiredLabel label="氏名" />
        <div class="register__label-group">
          <label class="register__label" for="last-name">
            <FormInput
              v-model="lastName"
              id="last-name"
              placeholder="性"
              required
              maxlength="255"
              autofocus
            />
            <InputErrorLabel :errors="errors" id="last-name" />
          </label>
          <label class="register__label" for="first-name">
            <FormInput
              v-model="firstName"
              id="first-name"
              placeholder="名"
              required
              maxlength="255"
            />
            <InputErrorLabel :errors="errors" id="first-name" />
          </label>
        </div>
      </div>
      <div class="register__section">
        <RequiredLabel label="ふりがな" />
        <div class="register__label-group">
          <label class="register__label" for="last-name-kana">
            <FormInput
              v-model="lastNameKana"
              id="last-name-kana"
              placeholder="せい"
              required
              maxlength="255"
            />
            <InputErrorLabel :errors="errors" id="last-name-kana" />
          </label>
          <label class="register__label" for="first-name-kana">
            <FormInput
              v-model="firstNameKana"
              id="first-name-kana"
              placeholder="めい"
              required
              maxlength="255"
            />
            <InputErrorLabel :errors="errors" id="first-name-kana" />
          </label>
        </div>
      </div>
      <div class="register__section">
        <RequiredLabel label="生年月日" />
        <label class="register__label" for="birthday">
          <FormInput
            :modelValue="birthdayToString"
            id="birthday"
            placeholder="yyyy年MM月dd日"
            required
            pattern="^\d{4}-\d{2}-\d{2}$"
            readonly
            type="hidden"
          />
          <Datepicker
            v-model="birthday"
            locale="ja"
            :previewFormat="null"
            format="yyyy-MM-dd"
            :enableTimePicker="false"
            :autoApply="true"
            inputClassName="dp__custom-input"
            :maxDate="new Date()"
          ></Datepicker>
          <InputErrorLabel :errors="errors" id="birthday" />
        </label>
      </div>
      <div class="register__section">
        <RequiredLabel label="住所" />
        <div class="register__label--address">
          <div>
            <div class="register__label-group register__label-group--post-code">
              <label
                class="register__label register__label--post-code-first"
                for="post-code-first"
              >
                <FormInput
                  v-model="postCodeFirst"
                  id="post-code-first"
                  placeholder="000"
                  required
                  pattern="^\d{3}$"
                />
              </label>
              <span>-</span>
              <label
                class="register__label register__label--post-code-last"
                for="post-code-last"
              >
                <FormInput
                  v-model="postCodeLast"
                  id="post-code-last"
                  placeholder="0000"
                  required
                  pattern="^\d{4}$"
                />
              </label>
            </div>
            <InputErrorLabel :errors="errors" id="post-code-last" />
          </div>
          <label class="register__label" for="first-address">
            <FormInput
              v-model="firstAddress"
              id="first-address"
              placeholder="市区町村"
              required
              maxlength="255"
            />
            <InputErrorLabel :errors="errors" id="first-address" />
          </label>
          <label class="register__label" for="last-address">
            <FormInput
              v-model="lastAddress"
              id="last-address"
              placeholder="建物名 部屋番号(省略可)"
              maxlength="255"
            />
            <InputErrorLabel :errors="errors" id="last-address" />
          </label>
        </div>
      </div>
      <div class="register__section">
        <RequiredLabel label="メールアドレス" />
        <label class="register__label" for="mail">
          <FormInput
            v-model="mail"
            id="mail"
            placeholder="example@xxx.com"
            required
            type="email"
            maxlength="256"
          />
          <InputErrorLabel :errors="errors" id="mail" />
        </label>
      </div>
      <div class="register__section">
        <RequiredLabel label="パスワード" />
        <label class="register__label" for="password">
          <div>
            <FormInput
              v-model="password"
              id="password"
              type="password"
              required
              minlength="8"
              maxlength="32"
            />
            <span class="register__annotation">※8桁以上 半角英数字記号</span>
          </div>
          <InputErrorLabel :errors="errors" id="password" />
        </label>
      </div>
      <div class="register__section">
        <RequiredLabel label="パスワード再入力" />
        <label class="register__label" for="password_confirmation">
          <FormInput
            v-model="passwordConfirm"
            id="password_confirmation"
            type="password"
            required
            minlength="8"
            maxlength="32"
          />
          <InputErrorLabel :errors="errors" id="password_confirmation" />
        </label>
      </div>
      <div class="register__footer">
        <SubmitButton size="md">登録</SubmitButton>
        <a class="register__link" href="/login">ログインへ</a>
      </div>
    </form>
  </section>
</template>

<script>
import FormInput from "~/components/input/FormInput.vue";
import RequiredLabel from "~/components/label/RequiredLabel.vue";
import InputErrorLabel from "~/components/label/InputErrorLabel.vue";
import SubmitButton from "~/components/button/SubmitButton.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import format from "date-fns/format";

export default {
  components: {
    FormInput,
    RequiredLabel,
    InputErrorLabel,
    SubmitButton,
    Datepicker,
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
      lastName: "",
      firstName: "",
      lastNameKana: "",
      firstNameKana: "",
      birthday: "",
      postCodeFirst: "",
      postCodeLast: "",
      firstAddress: "",
      lastAddress: "",
      mail: "",
      password: "",
      passwordConfirm: "",
    };
  },
  created() {
    this.lastName = this.old["last-name"] ?? "";
    this.firstName = this.old["first-name"] ?? "";
    this.lastNameKana = this.old["last-name-kana"] ?? "";
    this.firstNameKana = this.old["first-name-kana"] ?? "";
    this.birthday = new Date(this.old["birthday"] ?? "2000-01-01");
    this.postCodeFirst = this.old["post-code-first"] ?? "";
    this.postCodeLast = this.old["post-code-last"] ?? "";
    this.firstAddress = this.old["first-address"] ?? "";
    this.lastAddress = this.old["last-address"] ?? "";
    this.mail = this.old["mail"] ?? "";
  },
  computed: {
    birthdayToString() {
      return format(this.birthday, "yyyy-MM-dd");
    },
  },
};
</script>

<style scoped lang="scss">
.register {
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
    gap: 32px;
    width: 100%;
  }

  &__section {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
  }

  &__label-group {
    display: flex;
    gap: 16px;
    align-items: center;

    &--post-code {
      gap: 8px;
    }
  }

  &__label {
    width: 100%;
    display: flex;
    flex-direction: column;

    &--post-code-first {
      width: 44px;
    }

    &--post-code-last {
      width: 52px;
    }

    &--address {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }
  }

  &__annotation {
    font-size: var(--font-sm);
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

<!-- Datepicker用のグローバルなcss -->
<style>
.dp__custom-input {
  border: solid 1px;
  border-radius: 0;
}
</style>