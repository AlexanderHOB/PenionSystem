<template>
<v-container fluid grid-list-xl>
  <v-layout row wrap class="pt-3">
    <v-flex xs8>
      <v-layout row wrap>
        <v-flex xs12>
          <v-layout justify-space-around>
            <v-flex xs4>
              <v-select
                :items="categorias"
                label="Categorias"
              ></v-select>
            </v-flex>
            <v-flex xs5>
              <!-- <v-text-field
                v-model="searchQueryModel"
                solo
                :label="searchPlaceholder"
                color="blue"
                append-icon="search"
                :disabled="searchDisabled"
              ></v-text-field> -->
              <v-text-field
                solo
                label="Buscar platillo..."
                color="blue"
                append-icon="search"
              ></v-text-field>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex class="platillo" xs4>
          <mozo-platillo-box class="platillo-bg" :addPlatillo="addPlatillo" :index="1" />
          <p class="platillo-text text-center">Porción de arroz blanco</p>
          <span class="platillo-add">+</span>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex xs4>
      <v-card>
        <v-card-title class="pb-5">
          <v-spacer></v-spacer>
          <h2>PEDIDO</h2>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-card-text class="black--text">
          <v-layout row wrap>
            <v-flex xs4><strong>N° pedido</strong></v-flex>
            <v-flex xs8>12345</v-flex>
            <v-flex xs4><strong>Mozo</strong></v-flex>
            <v-flex xs8>luisMozo</v-flex>
            <v-flex xs4><strong>Mesa</strong></v-flex>
            <v-flex xs8>1</v-flex>
            <v-flex xs12>
              <!-- <v-data-table
                :headers="headers"
                :items="ordenes"
                class="elevation-1"
              >
                <template v-slot:items="props">
                  <td class="text-xs-center px-0">
                    
                  </td>
                  <td class="text-xs-left px-0">{{ props.item.platillo }}</td>
                  <td class="text-xs-center px-0">{{ props.item.cantidad }}</td>
                  <td class="text-xs-center px-0">{{ props.item.valor }}</td>
                </template>
              </v-data-table> -->

              <v-data-table
                :headers="headers"
                :items="ordenes"
                class="elevation-1"
              >
                <template v-slot:item.action="{ item }">
                  <div class="text-center px-0">
                    <img src="../../assets/img/mozo/eliminar.svg" alt="aumentar" class="actions">
                    <img src="../../assets/img/mozo/aumentar.svg" alt="aumentar" class="actions">
                    <img src="../../assets/img/mozo/disminuir.svg" alt="aumentar" class="actions">
                  </div>
                </template>
              </v-data-table>
            </v-flex>
          </v-layout>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn class="yellow" rounded>Comanda</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</v-container>
</template>

<script>
import MozoPlatilloBox from '../../components/box/MozoPlatilloBox';
import { mapState, mapMutations, mapActions } from 'vuex';

export default {
  data(){
    return {
      categorias: [
        'entradas',
        'arroces'
      ],
      headers: [
        { text: 'Actions', align: 'center', sortable: false, value: 'action' },
        { text: 'Pedido', align: 'center', width: 120, sortable: false, value: 'platillo' },
        { text: 'Cant', align: 'center', class: 'px-0', value: 'cantidad' },
        { text: 'Valor', align: 'center', class: 'px-0', value: 'valor' },
      ],
      ordenes: [
        {
          platillo: 'Arroz Chaufa',
          msg: 'poca cantidad de sal',
          cantidad: 1,
          valor: 4.00
        },
        {
          platillo: 'Porcion de arroz blanco',
          msg: 'poca cantidad de sal',
          cantidad: 2,
          valor: 2.00
        }
      ]
    }
  },
  components: {
    MozoPlatilloBox
  },
  methods: {
    addPlatillo(){

    },
    ...mapMutations(['breadcrumbMutation'])
  },
  mounted(){
    this.breadcrumbMutation('Mozo \\ Menu');
  }
}
</script>

<style lang="scss" scoped>
.platillo {
  position: relative;
  font-family: 'Concert One', cursive;
  &-text {
     position: absolute;
    top: 45%;
    left: 40%;
    transform: translateY(-50%);
    font-size: 16px;
    width: 45%;
    line-height: 100%;
  }
  &-add {
    position: absolute;
    top: 45%;
    left: 11%;
    transform: translateY(-50%);
    color: #fff;
    font-size: 64px;
    pointer-events: none;
  }
}
.actions {
  display: inline-block;
  width: 32px;
}
</style>

