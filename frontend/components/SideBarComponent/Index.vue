<template>
  <v-navigation-drawer
      :value="drawer"
      :mini-variant="miniVariant"
      :clipped="clipped"
      fixed
      app
      @input="handleChangeDrawer($event)"
    >
      <v-list-item>
        <v-list-item-action>
            <v-icon>{{ icon }}</v-icon>
          </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title class="text-h6">
            <v-icon>{{ title }}</v-icon>
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list>
        <v-list-item
          v-for="(item, i) in items"
          :key="i"
          :to="item.to"
          router
          exact
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <template #append>
        <div class="d-flex justify-center">
          <v-btn icon @click.stop="miniVariant = !miniVariant">
            <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'SideBarComponent',
    data: () => ({
      fixed: true,
      clipped: false,
      miniVariant: false,
      title: 'Challenges',
      icon: 'mdi-xml',
      items: [
        {
          icon: 'mdi-map-search',
          title: 'Address Search',
          to: '/',
        },
        {
          icon: 'mdi-caps-lock',
          title: 'Get Capital Letters',
          to: '/capital-letters',
        },
        {
          icon: 'mdi-file-document-alert',
          title: 'Document Validate',
          to: '/document-validate',
        }
      ],
    }),
    computed: {
      ...mapGetters({
        drawer: 'utils/getDrawerState',
      }),
    },
    methods: {
      handleChangeDrawer(event) {
        this.$store.dispatch('utils/drawer', event);
      }
    }
  }
</script>

<style scoped>

</style>