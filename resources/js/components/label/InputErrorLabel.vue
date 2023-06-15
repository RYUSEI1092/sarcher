<template>
    <span v-if="error" class="danger">{{ error }}</span>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      default: () => "",
    },
    errors: {
      type: Object,
      default: () => ({}),
    },
  },
  computed: {
    error() {
      return this.errors?.[this.id]?.[0]
    }
  },
};
</script>

<style lang="scss" scoped>
.danger {
  color: var(--danger-color);
  font-size: var(--font-sm);
  font-weight: bold;
}
</style>
