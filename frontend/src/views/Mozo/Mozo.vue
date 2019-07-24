<template>
  <div>
    <header class="mozo-header-bg white--text">
      <v-container class="mozo-header pb-2">
        <v-layout row wrap class="mozo-header-layout">
          <v-flex xs4></v-flex>
          <v-flex xs4 class="pt-1">
            <div class="mozo-header-breadcrumbBox d-flex align-center fill-height">
              <p class="mozo-header-breadcrumb mb-0"><span class="yellow--text">El Peñon \</span> {{ breadcrumb }}</p>
          </div>
          </v-flex>
          <v-flex xs12 sm3 class="mozo-header-user pt-1">
            <div class="mozo-header-userBox fill-height">
              <p class="mb-0 mozo-header-userText pl-3 pr-5">LuisMozo</p>
              <v-img
                :src="require('../../assets/iconos/administrador.svg')"
                alt="user"
                class="mozo-header-userImg"
                contain
                height="40"
              ></v-img>
            </div>
          </v-flex>
          <v-flex xs4 class="pt-4">
            <router-link class="mozo-buttonBox" :to="{name: 'mozo-mesas'}">
              <img src="../../assets/img/mozo/etiqueta.svg" alt="mozo button" class="mozo-buttonImg">
              <p class="pb-0 mozo-buttonText"><img src="../../assets/img/mozo/mesas.svg" alt="mesas" class="mozo-buttonIcon"><span>Mesas</span></p>
            </router-link>
          </v-flex>
          <v-flex xs4 class="pt-4">
            <router-link class="mozo-buttonBox" :to="{name: 'mozo-menu'}">
              <img src="../../assets/img/mozo/etiqueta.svg" alt="mozo button" class="mozo-buttonImg">
              <p class="pb-0 mozo-buttonText"><img src="../../assets/img/mozo/menu.svg" alt="mesas" class="mozo-buttonIcon"><span>Menú</span></p>
            </router-link>
          </v-flex>
        </v-layout>
      </v-container>
      <div class="mozo-header-waves">
        <v-img
          :src="require('../../assets/img/header/wave.png')"
          contain
          height="80"
          class="mozo-header-waves-img"
          position="center bottom"
        ></v-img>
      </div>
    </header>
    <router-view />
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';

export default {
  methods: {
    search(){},
    ...mapMutations(['breadcrumbMutation'])
  },
  computed: {
    ...mapState(['breadcrumb'])
  },
  mounted(){
    this.breadcrumbMutation('Mozo');
  }
}
</script>


<style lang="scss" scoped>
.mozo {
  &-header {
    &-bg {
      position: relative;
      @extend %bg-primary;
    }
    &-layout {
      position: relative;
      z-index: 2;
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
      width: 180px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    &-user {
      display: flex;
      justify-content: flex-end;
    }
    &-userImg {
      height: 100%;
      background-color: rgba(255, 255, 255, .3);
    }
      &-waves {
      position: absolute;
      bottom: 0;
      left: 0;
      z-index: 1;
      height: 80px;
      width: 100%;
      opacity: .5;
    }
  }
  &-buttonBox {
    position: relative;
    width: 70%;
    display: block;
  }
  &-buttonText {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    justify-content: center;
    align-items: center;
    color: #000;
    font-size: 20px;
  }
  &-buttonIcon {
    width: 40px;
    margin-right: 16px;
    transform: translateY(-5px)
  }
}
</style>
