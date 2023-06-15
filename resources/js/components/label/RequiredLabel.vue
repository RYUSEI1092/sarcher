<template>
  <div>
    <span class="required">必須</span>
    <span class="label">{{ label }}</span>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: () => "Propsが未入力です",
    },
  },
};
</script>

<style lang="scss" scoped>
.required {
  padding: 2px 4px 0;
  color: var(--secondary-color);
  background-color: var(--danger-color);
  font-size: var(--font-md);
  font-weight: bold;
}

.label {
  margin-left: 8px;
  font-size: var(--font-lg);
}
</style>
