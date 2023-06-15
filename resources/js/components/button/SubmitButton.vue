<template>
  <button
    class="submit"
    :class="[`submit--${size}`, { 'submit--reversal': isReversal }]"
    type="submit"
  >
    <slot></slot>
  </button>
</template>

<script>
export default {
  props: {
    size: {
      type: String,
      default: () => "md",
    },
    isReversal: {
      type: Boolean,
      default: () => false,
    },
  },
  methods: {
    update(e) {
      console.log(e.target.value);
      this.$emit("update:modelValue", e.target.value);
    },
  },
};
</script>

<style lang="scss" scoped>
.submit {
  border: 1px solid var(--main-color);
  background-color: var(--main-color);
  color: var(--secondary-color);
  font-weight: bold;

  &--lg {
    padding: 19px;
    font-size: var(--font-xl);
  }

  &--md {
    padding: 13px;
    font-size: var(--font-lg);
  }

  &--sm {
    padding: 7px;
    font-size: var(--font-md);
    font-weight: normal;
  }

  &--reversal {
    background-color: var(--secondary-color);
    color: var(--main-color);
  }
}
</style>
