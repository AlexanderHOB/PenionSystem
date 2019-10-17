<template>
  <v-row>
    <v-col cols="5"></v-col>
    <v-col cols="7">
      <v-container fluid>
        <h3 class="title">Mesas Ocupadas</h3>
        <v-row>
          <v-col
            v-for="n in 3"
            :key="n"
            cols="4"
          >
            <v-card>
              <div class="d-flex flex-no-wrap justify-space-between">
                <div>
                  <v-card-title
                    class="title"
                  >
                    Mesa 61
                  </v-card-title>

                  <v-card-text>
                    <p class="Mesa-capacidad mb-1">Capacidad</p>
                    25
                  </v-card-text>
                </div>

                <div class="Mesa-img-box pa-2" :style="{'background-color':  '#2151aa'}">
                  <img src="../../assets/img/mesas/mesaOcupada.svg" alt="mesa" class="Mesa-img">
                </div>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-col>
  </v-row>
</template>

<script>
export default {
  
}
</script>

<style lang="scss" scoped>
.Mesa {
  &-img {
    &-box {
      width: 120px;
    }
  }
  &-capacidad {
    color: #212121;
  }
}
</style>