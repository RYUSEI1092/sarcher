<template>
  <div id="bookShelf">
    <div class="return">
      <h1>本棚</h1>
      <SubmitButton size="sm" @click="onClickReturnBooks" :disabled="!selectedIds.length" :isReversal="!selectedIds.length">
        返却
      </SubmitButton>
    </div>
    <form v-if="extendsRentals.length" method="POST" action="/bookshelf" ref="form">
      <template v-for="rental in extendsRentals" :key="rental.day">
        <p :class="rental.css">
          {{ rental.days_left }}<small>{{ rental.comments }}</small>
        </p>
        <label for="check">
          <div v-for="book in rental.books" class="book" :key="book.id">
            <img :src="book.book_img" alt="" />
            <ul>
              <li class="title">{{ book.book_title }}</li>
              <li>{{ book.book_author }}</li>
              <li class="cap">出版社</li>
              <li>{{ book.publisher_name }}</li>
              <li class="cap">ISBN</li>
              <li>{{ book.isbn }}</li>
              <li class="cap">図書館名</li>
              <li>{{ book.library }}</li>
            </ul>
            <input
              v-model="selectedIds"
              type="checkbox"
              name="ids[]"
              :value="book.id"
            />
          </div>
        </label>
      </template>
      <input type="hidden" name="_token" :value="csrf" />
    </form>
    <div v-else class="no-rentals">
      <p class="no-rentals__count">0冊</p>
      <p>現在借りている本はありません</p>
      <p>新しい本を見つけてレンタルしよう！</p>
      <form action="/books">
        <SubmitButton class="no-rentals__submit">検索</SubmitButton>
      </form>
    </div>
    <RentalConfirmModal
      :isShow="isShowRentalReturnConfirm"
      :titles="selectedTitles"
      :booksCount="booksCount"
      left-message="いいえ"
      right-message="はい"
      @close="onClose"
      @click-left="onClose"
      @click-right="onSubmit"
    />
    <ConfirmModal
      :isShow="isShowRentalReturnComplete"
      message="返却ありがとうございます"
      left-message="本棚"
      left-icon="bookshelf"
      right-message="トップ"
      right-icon="home"
      @close="onClose"
      @click-left="onClose"
      @click-right="toHome"
    />
  </div>
</template>

<script>
import RentalConfirmModal from "~/components/modal/RentalConfirmModal.vue";
import ConfirmModal from "~/components/modal/ConfirmModal.vue";
import SubmitButton from "~/components/button/SubmitButton.vue";

export default {
  components: { RentalConfirmModal, ConfirmModal, SubmitButton },
  props: {
    csrf: {
      type: String,
      default: () => "",
    },
    isUpdate: {
      type: Boolean,
      default: () => false,
    },
    rentals: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      isShowRentalReturnConfirm: false,
      isShowRentalReturnComplete: false,
      selectedIds: [],
    };
  },
  created() {
    this.isShowRentalReturnComplete = this.isUpdate;
  },
  computed: {
    books() {
      return this.extendsRentals.flatMap((rental) => rental.books);
    },
    selectedTitles() {
      return this.books
        .filter((book) => this.selectedIds.includes(book.id))
        .map((book) => book.book_title);
    },
    booksCount() {
      return this.books.length;
    },
    extendsRentals() {
      return this.rentals.map((rental) => ({
        ...rental,
        css: this.extendsCss(rental.day),
      }));
    },
  },
  methods: {
    extendsCss(day) {
      if (day === -1) return ["warning"];
      if (day === 0) return ["today"];
      return ["after"];
    },
    onClickReturnBooks() {
      this.isShowRentalReturnConfirm = true;
    },
    onClose() {
      this.isShowRentalReturnConfirm = false;
      this.isShowRentalReturnComplete = false;
    },
    onSubmit() {
      this.$refs.form.requestSubmit();
    },
    toHome() {
      location.href="/";
    },
  },
};
</script>


<style lang="scss" scope>
#bookShelf {
  label {
    display: block;
  }

  margin: 0 32px;

  .return {
    display: flex;
    justify-content: space-between;
    padding-bottom: 40px;

    h1 {
      font-weight: bold;
      font-size: 20px;
      height: fit-content;
      margin-top: 6px;
      margin-bottom: 0px;
    }

    button {
      width: 104px;
      border-radius: 3px;
    }
  }

  p.warning {
    color: red;
    font-size: 16px;
    font-weight: bold;

    small {
      margin-left: 8px;
      font-size: var(--font-xs);
      font-weight: 400;
    }
  }

  p.today {
    font-size: 16px;
    font-weight: bold;
    color: #ff7c02;
  }

  p.after {
    font-size: 16px;
    font-weight: bold;
    color: black;
  }
  .book {
    height: 180px;
    margin: 0px 0px 24px;
    display: flex;
    border-bottom: 1px solid #d3d3d3;
    padding-bottom: 16px;
    align-items: center;
    img {
      object-fit: contain;
      width: 121px;
      height: 100%;
    }

    ul {
      padding-left: 24px;
      margin-right: 8px;
      margin-bottom: 0px;
      list-style: none;
      flex: 1;
      
      li {
        overflow: hidden;
        font-size: 14px;
        margin-bottom: 8px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
      }

      li:last-child {
        font-size: 14px;
        margin-bottom: 0px;
      }

      li.cap {
        font-size: 12px;
        line-height: 12px;
        color: #576065;
        margin-bottom: 0px;
      }

      li.title {
        height: 32px;
        margin-bottom: 0px;
        line-height: 17px;
        font-size: 16px;
        font-weight: bold;
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
      }
    }

    input#check {
      width: 26px;
      height: 26px;
      margin-left: auto;
    }
  }
  .book:last-child {
    border-bottom: 0px solid;
  }

  .no-rentals {
    // 中央を出すために画面サイズからheader/footer/paddingを引いて算出
    margin-top: calc(calc(100vh / 2) - calc(var(--header-height) + var(--footer-height) + 115px));
    text-align: center;
    &__count {
      font-size: var(--font-xl);
      font-weight: bold;
    }
    &__submit {
      width: 204px;
      margin-top: 28px;
    }
  }
}
</style>
