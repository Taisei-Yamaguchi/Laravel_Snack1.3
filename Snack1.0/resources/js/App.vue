
<script setup>
import { ref } from 'vue'

const message = ref('Hello World!')
</script>

<template>
  <h1>{{ message }}</h1>
</template>

<template>
    App hello
    <router-view></router-view>
</template>