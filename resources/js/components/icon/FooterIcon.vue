<template>
  <a
    class="icon"
    :class="{ active: activeButton === icon.path }"
    :href="icon.view"
  >
    <img :src="getImageUrl" :alt="icon.alt" />
  </a>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: () => "home",
    },
    activeButton: {
      type: String,
      default: () => "home",
    },
  },
  computed: {
    icon() {
      const iconSettings = {
        home: { path: "home", alt: "ホーム", view: "/" },
        search: { path: "search", alt: "検索", view: "/books" },
        bookshelf: { path: "bookshelf", alt: "本棚", view: "/bookshelf" },
        mypage: { path: "mypage", alt: "マイページ", view: "/profile" },
        login: { path: "mypage", alt: "ログイン", view: "/login" },
      };
      return iconSettings[this.type];
    },
    getImageUrl() {
      // https://ja.vitejs.dev/guide/assets.html#new-url-url-import-meta-url
      return new URL(
        `../../../images/icons/${this.icon.path}.svg`,
        import.meta.url
      ).href;
    },
  },
};
</script>

<style lang="scss" scoped>
.icon {
  height: var(--footer-height);
  width: 100%;
  border-top: var(--footer-line-height) solid #d3d3d3;
  display: flex;
  align-items: center;
  justify-content: center;

  &.active {
    border-color: #000;
  }
}
</style>