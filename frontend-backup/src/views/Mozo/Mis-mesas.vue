<template>
  <v-layout>
    <v-flex xs8>
      <v-layout row wrap>
        <v-flex xs12 sm6 md4 lg3>
          <v-card class="mesa-card">
            <v-img
            :src="require('../../assets/img/mesas/mesaOcupada.svg')"
            contain
            height="100"
            class="mesa"
            ></v-img>
            <v-card-title class="pt-0">
                <!-- <h2>{{ mesa.numero }}</h2> -->
                <h2>1</h2>
                <div class="mesa-color"  :style="{'background-color':  '#2151aa'}"></div>
            </v-card-title>
            <v-card-text class="py-0">
              <!-- <p><strong>Capacidad: {{ mesa.capacidad }} {{ mesa.capacidad == 1 ? 'persona' : 'personas' }}</strong></p> -->
              <p><strong>Capacidad: 1 persona</strong></p>
              <p>descripción</p>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex xs4>
      <v-card>
        <v-container>
          <v-card-title>
          <v-spacer></v-spacer>
          <h2>Mesa Número 1</h2>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-card-text>
          <p>Capacidad: 25 personas</p>
          <p>Descripción: Mesa con reserva</p>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat color="green">Ver pedidos</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
        </v-container>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<style lang="scss" scoped>
.mesa {
  transform: translateY(25px);
  &-card {
    position: relative;
  }
  &-color {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 25px;
    height: 25px;
    border-radius: 50%;
  }
}
</style>
