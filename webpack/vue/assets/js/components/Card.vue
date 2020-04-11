<script>
  export default {
    name: 'Card',
  };
</script>

<template>
    <div class="rounded overflow-hidden shadow-lg mt-2">
        <div class="px-4 py-2">
            <slot />
        </div>
    </div>
</template>
