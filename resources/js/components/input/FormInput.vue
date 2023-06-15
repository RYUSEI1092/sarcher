<template>
  <input
    class="input"
    :id="id"
    :type="type"
    :name="id"
    :value="modelValue"
    :autocomplete="id"
    @change="update"
  />
</template>

<script>
export default {
  props: {
    modelValue: {
      type: String,
      default: () => "",
    },
    id: {
      type: String,
      default: () => "",
    },
    type: {
      type: String,
      default: () => "text",
    },
  },
  methods: {
    update(e) {
      this.$emit("update:modelValue", e.target.value);
    },
  },
};
</script>

<style lang="scss" scoped>
.input {
  width: 100%;
  border-radius: 0;
  border: 1px solid black;
  padding: 4px 8px;
}
</style>
