<script>
  import TopPerson from './TopPerson';

  export default {

    name: 'TopPersons',
    components: {
      TopPerson,
    },
    props: {
      topPersons: {
        type: Array,
        required: true,
      },
      isLoading: {
        type: Boolean,
        required: true,
      },
    },
  };
</script>

<template>
    <div>
        <div
            v-if="isLoading"
            class="text-center py-2"
        >
            Chargement...
        </div>
        <div v-else class="flex flex-row flex-wrap -mx-2">
            <div
                v-for="(topPerson) in topPersons"
                :key="topPerson.personId"
                class="w-1/5 p-2"
            >
                <TopPerson :top-person="topPerson" />
            </div>
        </div>
    </div>
</template>
