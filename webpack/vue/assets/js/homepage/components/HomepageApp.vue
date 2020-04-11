<script>
  import TopPersons from './TopPersons';
  import Filters from './Filters';

  export default {
    name: 'HomepageApp',
    components: { Filters, TopPersons },
    data() {
      return {
        isLoading: false,
        topPersons: [],
        nameFilter: '',
      };
    },
    computed: {
      filteredTopPersons() {
        const nameFilterLowercase = this.nameFilter.toLowerCase();

        return this.topPersons.filter((topPerson) => (
          topPerson.personName.toLowerCase().search(nameFilterLowercase) !== -1
        ));
      },
    },
    mounted() {
      this.isLoading = true;

      fetch('/api/persons/top')
        .then((response) => {
          response.json()
            .then((responseJson) => {
              this.topPersons = responseJson;
              this.isLoading = false;
            });
        });
    },
    methods: {
      onNameFilterChange(nameFilter) {
        this.nameFilter = nameFilter;
      },
    },
  };
</script>

<template>
    <div class="container mx-auto">
        <Heading2>Classement des meilleurs joueurs :</Heading2>
        <Filters :name-filter="nameFilter" @name-filter-change="onNameFilterChange" />
        <TopPersons :top-persons="filteredTopPersons" :is-loading="isLoading" />
    </div>
</template>
