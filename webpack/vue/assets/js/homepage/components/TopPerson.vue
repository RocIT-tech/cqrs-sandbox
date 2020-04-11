<script>
  export default {
    name: 'TopPerson',
    props: {
      topPerson: {
        type: Object,
        required: true,
      },
    },
  };
</script>

<template>
    <Card :class="{'bg-green-200': topPerson.isWinner}">
        <div class="font-bold text-xl mb-2">
            {{ topPerson.personName }}
            <i v-if="topPerson.isWinner" class="fas fa-crown float-right" />
        </div>
        <div>
            <strong>Rang moyen :</strong>
            #{{ (null !== topPerson.averageRank) ? topPerson.averageRank : '?' }}
        </div>
        <div class="mt-2">
            <strong>Nombre de parties :</strong>
            {{ topPerson.gameCount }}
        </div>
        <div class="mt-2">
            <strong>Nombre de victoires :</strong>
            {{ topPerson.victoryCount }}
        </div>
    </Card>
</template>
