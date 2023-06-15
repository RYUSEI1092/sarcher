<template>
  <div class="main__container">
    <h1 class="rank">⭐️人気ランキング⭐️</h1>
    <div class="ranking">
      <ul class="contents">
        <template v-for="book in favorites" :key="book.isbn">
          <book-card disp-type="ranking" :book="book"></book-card>
        </template>
      </ul>
    </div>

    <h1 class="history">最近読まれた本</h1>
    <div class="bookHistory">
      <ul class="contents">
        <template v-for="book in histories" :key="book.isbn">
          <book-card disp-type="history" :book="book"></book-card>
        </template>
      </ul>
    </div>
    <confirm-modal
      :isShow="isShowRegisteredModal"
      message="登録が完了しました。"
      center-message="閉じる"
      @click-center="closeModal"
    ></confirm-modal>
  </div>
</template>

<script>
import BookCard from "~/components/card/BookCard.vue";
import ConfirmModal from "~/components/modal/ConfirmModal.vue";

export default {
  components: {
    BookCard,
    ConfirmModal,
  },
  props: {
    favorites: {
      type: Array,
      default: () => [],
    },
    histories: {
      type: Array,
      default: () => [],
    },
    registerSuccess: {
      type: Boolean,
      default: () => false,
    },
  },
  data() {
    return {
      isShowRegisteredModal: false,
    };
  },
  created() {
    this.isShowRegisteredModal = this.registerSuccess;
  },
  methods: {
    closeModal() {
      this.isShowRegisteredModal = false;
    },
  },
};
</script>


<style lang="scss" scoped>
h1.rank {
  font-size: var(--font-xl);
  font-weight: bold;
  margin-bottom: 16px;
  margin-left: 16px;
}

.ranking {
  margin: 0 16px;
  ul.contents {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    padding-left: 0px;
  }
}

h1.history {
  font-size: var(--font-xl);
  font-weight: bold;
  margin-top: 16px;
  margin-bottom: 16px;
  margin-left: 16px;
}
.bookHistory {
  margin: 0 16px;
  width: calc(100% - 32px);
  overflow-x: scroll;
  ul.contents {
    width: calc(100% + 18px);
    display: flex;
    flex-wrap: nowrap;
    gap: 18px;
    padding-left: 0px;
  }
}
p{
  width: 119px;
}
</style>