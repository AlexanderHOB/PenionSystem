<template>
  <div class="login">
    <v-container grid-list-xl>
      <div class="login-bg"></div>
      <h1 class="login-title text-center">{{ title }}</h1>
      <v-layout row wrap>
        <v-flex xs4 v-for="(item, i) in items" :key="item.id">
          <div class="login-card" @click="openModal(i)">
            <img src="@/assets/img/login/logeo-inicio.svg" alt="Logeo" class="login-card-img">
            <h2 class="login-card-title text-center">{{ item.title }}</h2>
          </div>
        </v-flex>
      </v-layout>
    </v-container>

    <v-dialog
      v-model="modal"
      width="600"
    >
      <v-card class="modal">
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="modal-title">Login {{ modalTitle }}</h3>
          <v-spacer></v-spacer>
          <v-btn icon color="blue" @click="modal = false">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text class="pb-5">
          <h4 class="usaurios-title">Usuarios Permitidos:</h4>
          <ul class="usaurios text-center">
            <li v-for="(usuario, i) in usuarios" class="usaurios-item" :style="{'background-color':  usuario.color}" @click="openModalPassword(i)">{{ usuario.nombres }} {{ usuario.apellidos }}</li>
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
            <v-form
              ref="form"
              lazy-validation
              v-model="valid"
              @submit.prevent="login"
              autocomplete="off"
            >
              <v-container grid-list-lg>
                <v-layout>
                  <v-flex>
                    <v-text-field
                      v-model="password"
                      @input="clearError"
                      :append-icon="passwordShow ? 'visibility' : 'visibility_off'"
                      :type="passwordShow ? 'text' : 'password'"
                      :rules="[rules.required, rules.counterPass, rules.minPass]"
                      name="password"
                      label="Ingese su contraseña"
                      hint="Al menos 8 caracteres"
                      class="input-group--focused"
                      counter="20"
                      @click:append="passwordShow = !passwordShow"
                      :disabled="disabled"
                      :error="error.state"
                      :error-messages="error.message"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-form>
          </v-card-text>
          <v-card-actions class="pb-4">
            <v-spacer></v-spacer>
            <v-btn icon color="red" @click="modalPassword = false" :disabled="disabled"><v-icon>close</v-icon></v-btn>
            <v-btn icon color="green" @click="login" :loading="loginLoad" :disabled="!valid"><v-icon>done</v-icon></v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-dialog>

  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  data(){
    return {
      title: 'Loading...',
      items: [],
      modal: false,
      modalTitle: '',
      usuarios: [],
      name: '',
      password: '',
      index: 0,
      modalPassword: false,
      passwordShow: false,
      disabled: false,
      loginLoad: false,
      error: {
        state: false,
        message: ''
      },
      // Datos para valdiar formulario
      valid: true,
      rules: {
        required: value => !!value || 'Required.',
        counterPass: value => value.length <= 20 || '20 carácteres como máximo',
        minPass: value => value.length >= 8 || '8 Carácteres como mínimo'
      }
    }
  },
  methods: {
    // OBTENER USUARIOS
    async getUsuarios () {
      try {
        if (this.allUsuariosState.length === 0) {
          await this.allUsuariosAction()
        }

        if (this.allUsuariosState.data) {
          let usuarios = this.allUsuariosState.data
          if (usuarios.length > 0) {

            usuarios = usuarios.filter(e => e.condicion)

            let roles = []
            
            usuarios.forEach((e, i) => {
              roles[i] = e.rol
            });

            // roles = roles.filter((e, i, arr) => {
            //   return arr.indexOf(e) === i
            // })
            roles = [... new Set(roles)]

            roles.forEach(e => {
              const user = usuarios.filter(element => {
                return element.rol == e
              })
              this.items.push({
                title: e,
                usuarios: user
              })
            })
          }
        }
        this.title = 'Bienvenido'
      } catch (error) {
        this.title = 'Error'
      }
    },

    // Open Modal
    openModal (index) {
      this.modalTitle = this.items[index].title
      this.usuarios = this.items[index].usuarios
      this.modal = true
    },

    // Open modal password
    openModalPassword (index) {
      this.name = `${this.usuarios[index].nombres} ${this.usuarios[index].apellidos}`
      this.index = index
      this.modalPassword = true
    },

    // Limpiar errores
    clearError () {
      if (this.error.state) {
        this.error.state = false
        this.error.message = ''
      }
    },

    // Login
    async login () {
      try {
        if(this.$refs.form.validate() && !this.error.state){
          this.disabled = true
          this.loginLoad = true
          let usuario = this.usuarios[this.index]

          const credentials =  {
            email: usuario.email,
            password: this.password,
            remember_me: false,
          }

          await this.login(credentials)

          this.modal = false
          this.$router.push({ name: 'admin' })
        }
      } catch (error) {
        this.error.state = true
        this.error.message = 'Password invalida'
      } finally {
        this.disabled = false
        this.loginLoad = false
      }
    },

    ...mapActions(['allUsuariosAction', 'login'])
  },
  computed: {
    ...mapState(['allUsuariosState'])
  },
  watch: {
    modalPassword () {
      if (!this.modalPassword) {
        this.password = ''
        this.$refs.form.resetValidation()
      }
      this.clearError()
    }
  },
  created () {
    this.getUsuarios()
  }
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
      line-height: 100%;
      transition: .3s;
    }
  }
}
.modal {
  font-family: 'Concert One', cursive;
  font-weight: 400;
  &-title {
    background-image: linear-gradient(#0286CF 0% ,#26C4D8 100%);
    background-clip: text;
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
