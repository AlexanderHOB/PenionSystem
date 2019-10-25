<template>
  <header class="header-bg white--text">
    <v-container fluid class="header pt-0 pb-0">
      <v-layout row wrap class="header-layout">
        <v-flex xs12 sm3 class="pt-5">
          <div class="hidden-md-and-down header-miniIconBox justify-center align-center fill-height" @click.stop="miniMutation(!miniState)" v-ripple>
            <img
              src="../assets/iconos/pez.svg"
              alt="pez"
              class="header-miniIcon"
              :class="{'header-miniIcon-active': !miniState}"
            >
          </div>
          <div class="hidden-lg-and-up header-miniIconBox justify-center align-center fill-height" @click="drawerMutation(!drawerState)" v-ripple>
            <img
              src="../assets/iconos/pez.svg"
              alt="pez"
              class="header-miniIcon"
            >
          </div>
        </v-flex>
        <v-flex xs12 sm6 class="pt-5">
          <div class="header-breadcrumbBox d-flex align-center fill-height">
            <p class="header-breadcrumb mb-0"><span class="yellow--text">El Peñon \</span> {{ breadcrumb }}</p>
          </div>
        </v-flex>
        <v-flex xs12 sm3 class="header-user pt-5">
          <div class="header-userBox fill-height">
            <p class="mb-0 header-user-text pl-2 pr-4">Admin</p>
            <v-img
              :src="require('../assets/iconos/administrador.svg')"
              alt="user"
              class="header-user-img"
              height="40"
              contain
              position="10px"
            ></v-img>
          </div>
        </v-flex>
      </v-layout>
      <v-layout row wrap class="header-layout">
        <v-flex xs12 sm6 class="pt-5">
          <form @submit.prevent="search">
            <v-text-field
              v-model="searchQueryModel"
              solo
              :label="searchPlaceholder"
              color="blue"
              append-icon="search"
              :disabled="searchDisabled"
            ></v-text-field>
          </form>
        </v-flex>
        <v-flex sm2 class="pt-5 mt-2 text-center text-sm-right">
            <v-btn dark color="red" rounded @click="logoutUI">Logout</v-btn>
        </v-flex>
        <v-flex v-if="headerActionCreate" xs12 sm2 class="pt-5 mt-2 text-center text-sm-right">
          <v-btn class="indigo white--text" rounded @click="createModalMutation(true)">
            <v-icon left>add_circle_outline</v-icon>
            Nuevo
          </v-btn>
        </v-flex>
        <v-flex v-if="headerActionReport" xs12 sm2 class="pt-5 mt-2 text-center text-sm-right">
          <v-btn class="deep-purple white--text" rounded>
            <v-icon left>insert_drive_file</v-icon>
            Reporte
          </v-btn>
        </v-flex>
      </v-layout>
      <v-layout class="header-waves">
        <v-flex xs12>
          <v-img
            :src="require('../assets/img/header/wave.png')"
            contain
            height="80"
            class="header-waves-img"
            position="center bottom"
          ></v-img>
        </v-flex>
      </v-layout>
    </v-container>
  </header>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'

export default {
  methods: {
    search(){},
    async logoutUI () {
      this.logout()
      this.$router.push({name: 'login'})
    },
    ...mapMutations(['drawerMutation', 'miniMutation', 'createModalMutation', 'searchQueryMutation', 'authMutation']),
    ...mapActions(['logout'])
  },
  computed: {
    ...mapState(['drawerState', 'miniState', 'createModalState', 'headerActionCreate', 'headerActionReport', 'breadcrumb', 'searchPlaceholder', 'searchQuery', 'searchDisabled']),
    searchQueryModel: {
      get() {
        return this.searchQuery
      },
      set(value) {
        this.searchQueryMutation(value)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.header {
  position: relative;
  padding-right: 85px;
  padding-left: 85px;
  &-bg {
    @extend %bg-primary;
  }
  &-layout {
    position: relative;
    z-index: 2;
  }
  &-miniIconBox {
    width: 80px;
    padding: 4px 16px 6px;
    @extend %header-bg-items;
    cursor: pointer;
    @extend %tap-highlight;
  }
  &-miniIcon {
    transition: .5s ease-in;
    @extend %tap-highlight;
    &-active {
      transform: rotateY(180deg);
    }
  }
  &-waves {
    position: absolute;
    bottom: 0;
    left: 0;
    z-index: 1;
    height: 80px;
    width: 100%;
    opacity: .9;
  }
  &-breadcrumbBox {
    padding-left: 16px;
    padding-right: 16px;
    @extend %header-bg-items;
  }
  &-breadcrumb {
    font-size: 20px;
  }
  &-userBox {
    @extend %header-bg-items;
    overflow: hidden;
    width: 130px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  &-user {
    display: flex;
    justify-content: flex-end;
    &-img {
      background-color: rgba(255, 255, 255, .3);
    }
  }
}
</style>

