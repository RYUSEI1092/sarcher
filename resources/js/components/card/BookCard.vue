<!-- このコンポーネントは ul の子要素として使用すること -->
<template>
  <li class="book-card" :class="`disp-type-${dispType}`">
    <a :href="`/books/${book.isbn}`">
      <img class="book-card__image" :src="book.img" alt="蔵書イメージ" />
      <p class="book-card__text title">{{ book.title }}</p>
      <p class="book-card__text genre">{{ book.genre }}</p>
    </a>
  </li>
</template>

<script>
export default {
  props: {
    dispType: {
      type: String,
      default: () => "ranking",
    },
    book: {
      type: Object,
      default: () => ({
        isbn: "",
        img: "",
        title: "",
        genre: "",
      }),
    },
  },
};
</script>

<style lang="scss" scoped>
// reset
a {
  color: inherit;
  text-decoration: none;
}

.book-card {
  display: flex;
  flex-direction: column;

  &.disp-type-ranking {
    width: calc((100% - 36px) / 3);
  }

  &.disp-type-history {
    width: calc((200% - 36px) / 6);
  }

  &__image {
    object-fit: contain;
    object-position: center;
    width: 100%;
    height: 170px;
  }

  &__text {
    margin-bottom: 0px;
    list-style: none;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;

    &.title {
      width: 119px;
      font-size: 16px;
      line-height: 16px;
      margin-top: 8px;
    }

    &.genre {
      font-size: 10px;
      line-height: 12px;
      transform: scale(0.8);
      translate: (-10%);
    }
  }
}
</style>