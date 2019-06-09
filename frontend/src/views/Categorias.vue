<template>

  <LoadingFish v-if="loadingFish" />

  <v-container grid-list-xl v-else>
    <v-layout row wrap>
      <v-flex xs12 v-if="messageCategorias">
        <ErrorMessage :errorMessage="messageCategorias" :refresh="getCategorias" />
      </v-flex>
      <template>
        <v-flex xs12 class="d-flex align-center">
          <h1 class="display-1">{{ title }}</h1>
          <div class="text-xs-right">
            <v-btn class="blue" dark fab small @click="getCategorias"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex xs4>
          <div class="categoria-box">
            <h2 class="categoria-title text-xs-center">Platos Familiares</h2>
            <CategoriaBox class="categoria-bg" :active="active" />
            <img src="../assets/iconos/edit.svg" alt="" class="categoria-edit">
            <img src="../assets/iconos/detail.svg" alt="" class="categoria-detail">
          </div>
        </v-flex>
      </template>
    </v-layout>
    
    <LoadingDialog />

  </v-container>
</template>

<script>
  import axios from 'axios';
  import LoadingDialog from '../components/LoadingDialog';
  import LoadingFish from '../components/LoadingFish';
  import CategoriaBox from '../components/categorias/CategoriaBox';
  import ErrorMessage from '../components/ErrorMessage';

  import { mapState, mapMutations } from 'vuex';

  export default {
    name: 'Categorias',
    components: {
      LoadingDialog,
      LoadingFish,
      CategoriaBox,
      ErrorMessage
    },
    data() {
      return {
        title: 'Categorias',
        active: true,
        messageCategorias: ''
      }
    },
    methods: {
      async getCategorias(){
        try {
          this.loadingTitleMutation('Accediendo a la información');
          this.loadingDialogMutation(true);

          let response = await axios.get(this.url + 'categoria/platillo', this.config);

          console.log(response);
          this.messageMesas = '';
        } catch (error) {
          console.log(error);
          this.messageMesas = 'Error al conctar con el servidor';
        }finally {
          this.loadingDialogMutation(false);
        }
      },
      ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation'])
    },
    computed: {
      ...mapState(['url', 'config', 'loadingFish', 'createModalState']),
      createModal: {
        get() {
          return this.createModalState
        },
        set(value) {
          this.createModalMutation(value)
        }
      }
    },
    created(){
      this.headerActionsMutation(false);
      this.loadingFishMutation(true);
    },
    async mounted(){
      this.breadcrumbMutation('Categorias');
      await this.getCategorias();
      this.loadingFishMutation(false);
    }
  }
</script>

<style lang="scss" scoped>
  .categoria {
    &-box {
      position: relative;
    }
    &-title {
      position: absolute;
      top: 45%;
      left: 65%;
      transform: translate(-50%, -50%);
      z-index: 2;
      font-size: 24px;
      line-height: 100%;
      font-family: 'Concert One', cursive;
    }
    &-edit,
    &-detail {
      position: absolute;
      bottom: 0;
      transform: translateY(25px);
      width: 50px;
      transition: .5s;
      cursor: pointer;
      &:hover {
        transform: translateY(25px) scale(1.1);
      }
    }
    &-edit {
      left: 40%;
    }
    &-detail {
      right: 15%;
    }
  }
</style>
