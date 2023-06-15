<template>
  <header class="header">
    <img class="logo" src="../../images/logo.png" alt="Renty" />
    <div v-if="!isAuthorized && !isNoHeaderPage" class="buttons-container">
      <form action="/login" method="get">
        <SubmitButton size="sm">ログイン</SubmitButton>
      </form>
      <form action="/register" method="get">
        <SubmitButton size="sm" :is-reversal="true">新規登録</SubmitButton>
      </form>
    </div>
    <div v-if="isAuthorized" class="buttons-container">
      <form id="id-logout" action="/logout" method="post">
        <input type="hidden" name="_token" :value="csrf" />
      </form>
      <SubmitButton size="sm" @click="onClickLogout">ログアウト</SubmitButton>
    </div>
    <ConfirmModal
      :is-show="isShowLogoutModal"
      message="本当にログアウトしますか？"
      left-message="いいえ"
      right-message="はい"
      @click-left="cancelLogout"
      @click-right="continueLogout"
    ></ConfirmModal>
  </header>
</template>

<script>
import SubmitButton from "~/components/button/SubmitButton.vue";
import ConfirmModal from "~/components/modal/ConfirmModal.vue";

export default {
  components: {
    SubmitButton,
    ConfirmModal,
  },
  props: {
    isAuthorized: {
      type: Boolean,
      default: () => false,
    },
    csrf: {
      type: String,
      default: () => "",
    },
  },
  data() {
    return {
      isShowLogoutModal: false,
      isLogouting: false,
    };
  },
  computed: {
    isNoHeaderPage() {
      return (
        location.pathname === "/login" || location.pathname === "/register"
      );
    },
  },
  methods: {
    onClickLogout() {
      this.isShowLogoutModal = true;
    },
    cancelLogout() {
      this.isShowLogoutModal = false;
    },
    continueLogout() {
      if (this.isLogouting) {
        return;
      }
      this.isLogouting = true;
      document.forms["id-logout"].submit();
    },
  },
};
</script>

<style lang="scss" scoped>
.header {
  z-index: 10;
  height: var(--header-height);
  padding-left: 16px;
  padding-right: 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: var(--secondary-color);
}

.logo {
  width: 120px;
  height: 30px;
}

.buttons-container {
  display: flex;

  > form {
    margin-right: 16px;
  }
}
</style>