<template>
  <div class="login">
    <v-container grid-list-xl>
      <div class="login-bg"></div>
      <h1 class="login-title text-xs-center">Bienvenido</h1>
      <v-layout row wrap>
        <v-flex xs4 v-for="(item, i) in items" :key="item.id" @click="openModal(i)">
          <div class="login-card">
            <img src="../assets/img/login/logeo-inicio.svg" alt="Logeo" class="login-card-img">
            <h2 class="login-card-title">{{ item.title }}</h2>
          </div>
        </v-flex>
      </v-layout>
    </v-container>

    <v-dialog
      v-model="modal"
      width="600"
    >
      <v-card class="modal">
        <v-card-title class="pb-0">
          <v-spacer></v-spacer>
          <h3 class="modal-title">Login {{ modalTitle }}</h3>
          <v-spacer></v-spacer>
          <v-btn icon class="blue--text" @click="modal = false">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text class="pb-4">
          <h4 class="usaurios-title">Usuarios Permitidos:</h4>
          <ul class="usaurios text-xs-center">
            <li v-for="(usuario, i) in usuarios" class="usaurios-item" :style="{'background-color':  usuario.color}" @click="openModalPassword(i)">{{ usuario.name }}</li>
          </ul>
        </v-card-text>
      </v-card>

          <v-dialog
      v-model="modalPassword"
      width="500"
    >
      <v-card class="modal">
        <v-card-title class="pb-0">
          <v-spacer></v-spacer>
          <h3 class="modal-password-title">{{ name }}</h3>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-card-text class="pb-4">
          <v-text-field
            :append-icon="passwordShow ? 'visibility' : 'visibility_off'"
            :type="passwordShow ? 'text' : 'password'"
            name="password"
            label="Ingese su contraseña"
            hint="Al menos 8 caracteres"
            class="input-group--focused"
            @click:append="passwordShow = !passwordShow"
          ></v-text-field>
        </v-card-text>
        <v-card-actions class="pb-4">
          <v-spacer></v-spacer>
          <v-btn flat color="green">Ingresar</v-btn>
          <v-btn flat color="red" @click="modalPassword = false">Cancelar</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>
    </v-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  data(){
    return {
      items: [
        {
          title: 'Administrador',
        usuarios: [
          {name: 'Tenorio Camarena Felix Martin', color: '#FBB03B'}
        ]
        },
        {
          title: 'Mozo',
          usuarios: [
            {name: 'Tenorio Camarena Felix Martin', color: '#FBB03B'},
            {name: 'Arteaga Saldaña Manuela', color: '#74CDD0'},
            {name: 'Tuesta Rengifo Jennifer', color: '#558EF7'}
        ]
        },
        {
          title: 'Caja',
          usuarios: [
          {name: 'Tuesta Rengifo Jennifer', color: '#558EF7'}
        ] 
        },
        {
          title: 'Title',
          usuarios: [
          {name: 'Tuesta Rengifo Jennifer', color: '#558EF7'}
        ] 
        },
        {
          title: 'Title',
          usuarios: [
          {name: 'Arteaga Saldaña Manuela', color: '#74CDD0'},
        ] 
        },
        {
          title: 'Title',
          usuarios: [
          {name: 'Tuesta Rengifo Jennifer', color: '##558EF7'}
        ] 
        },
      ],
      modal: false,
      modalTitle: '',
      usuarios: [],
      name: '',
      modalPassword: false,
      passwordShow: false
    }
  },
  methods: {
    openModal(index){
      this.modalTitle = this.items[index].title;
      this.usuarios = this.items[index].usuarios;
      this.modal = true;
    },
    openModalPassword(index){
      this.name = this.usuarios[index].name;
      this.modalPassword = true;
    }
  },
  computed: {
    ...mapState(['token'])
  },
}
</script>

<style lang="scss" scoped>
.login {
  position: fixed;
  width: 100%;
  height: 100vh;
  font-family: 'Concert One', cursive;
  &-bg {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: -1;
  // background-image: url('../assets/img/login/background - 2.jpg');
  // background-image: url('../assets/img/login/background - 10.jpg');
  background-image: url('../assets/img/login/background - 14.jpg');
  background-size: cover;
  background-position: center;
  filter: brightness(.6);
  }
  &-title {
    color: #fff;
    text-shadow: 3px 3px 5px rgba(0, 0, 0, 1);
    font-size: 48px;
    margin-bottom: 40px;
  }
  &-card {
    position: relative;
    background-image: linear-gradient(#0286CF 0% ,#26C4D8 100%);
    border-radius: 12px;
    box-shadow: 8px 8px 12px 0px rgba(0,0,0,0.75);
    cursor: pointer;
    &-img {
      opacity: .95;
    }
    &-title {
      position: absolute;
      top: 70%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #fff;
      font-size: 32px;
      letter-spacing: 2px;
      font-weight: 400;
      transition: .3s;
    }
  }
}
.modal {
  font-family: 'Concert One', cursive;
  font-weight: 400;
  &-title {
    background-image: linear-gradient(#0286CF 0% ,#26C4D8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 36px;
    transform: translateX(36px);
    font-weight: 400;
  }
  &-password {
    &-title {
      font-size: 20px;
      margin-bottom: 8px;
      font-weight: 400;
    }
  }
}
.usaurios {
  list-style: none;
  padding-left: 16%;
  padding-right: 16%;
  &-item {
    padding: 8px;
    font-size: 20px;
    margin-top: 16px;
    border-radius: 20px;
    cursor: pointer;
  }
  &-title {
    font-size: 32px;
   font-weight: 400;
  }
}
</style>
