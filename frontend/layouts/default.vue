<template>
  <v-app dark>
    <SideBarComponent />

    <AppBarComponent />

    <v-main>
      <v-container>
        <Nuxt />
      </v-container>
    </v-main>

    <FooterComponent />
  </v-app>
</template>

<script>
  export default {
    name: 'DefaultLayout',
  }
</script>
