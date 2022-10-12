<template>
  <v-app-bar :clipped-left="clipped" fixed app>
    <v-app-bar-nav-icon @click.stop="handleChangeDrawer()" />
    <v-toolbar-title>{{ title }}</v-toolbar-title>
  </v-app-bar>
</template>

<script>
  import { mapGetters } from 'vuex';
  export default {
    name: 'AppBarComponent',
    data: () => ({
      clipped: false,
      fixed: false,
      title: '',
    }),
    computed: {
      ...mapGetters({
        drawer: 'utils/getDrawerState',
      }),
    },
    methods: {
      handleChangeDrawer() {
        this.$store.dispatch('utils/drawer', !this.drawer);
      }
    }
  }
</script>

<style scoped>

</style>