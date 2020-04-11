<script>
  export default {
    name: 'Filters',
    props: {
      nameFilter: {
        type: String,
        required: true,
      },
    },
    methods: {
      onNameFilterInput(e) {
        this.$emit('name-filter-change', e.target.value);
      },
    },
  };
</script>

<template>
    <div>
        <label for="nameFilter">Rechercher un joueur :</label>
        <input
            id="nameFilter"
            type="text"
            class="border border-gray-400 rounded py-1 px-2 m-2 focus:shadow-inner"
            :value="nameFilter"
            @input="onNameFilterInput"
        >
    </div>
</template>
