<template>
  <loading-fish v-if="isLoading" />

  <v-container v-else fiuid>
    <header class="d-flex justify-space-between align-center my-3">
      <h3 class="title">Registrar Reservación</h3>
      <v-btn
        small
        fab
        dark
        color="blue"
        @click="refresh"
      >
        <v-icon>refresh</v-icon>
      </v-btn>
    </header>
    <v-data-table
      :headers="headers"
      :items="reservaciones"
      :items-per-page="5"
      class="elevation-1"
    ></v-data-table>

    <alert-notifications />

    <loading-dialog />

  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'

import LoadingFish from '@/components/loading/LoadingFish'
import AlertNotifications from '@/components/messages/AlertNotifications'
import LoadingDialog from '@/components/loading/LoadingDialog'

export default {
  components: {
    LoadingFish,
    AlertNotifications,
    LoadingDialog
  },
  data () {
    return {
      // Page
      isLoading: true,
      headers: [
        { text: 'Reserva', value: 'reserva' },
        { text: 'Nombre', value: 'nombre' },
        { text: 'Fecha de Reserva', value: 'fechaReserva' },
        { text: 'Fecha de Evento', value: 'fechEvento' },
        { text: 'Monto de Adelanto', value: 'montoAdelanto' }
      ],
      reservaciones: [
        {
          reserva: 'Matrimonio',
          nombre: 'Alama Gutierrez Rios',
          fechaReserva: '2017-12-22',
          fechEvento: '2018-10-13',
          montoAdelanto: 6000.00,
        },
        {
          reserva: 'Matrimonio',
          nombre: 'Alama Gutierrez Rios',
          fechaReserva: '2017-12-22',
          fechEvento: '2018-10-13',
          montoAdelanto: 6000.00,
        }
      ]
    }
  },
  methods: {
    async getAllEventos () {
      try {
        
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Ocurrio un error en el servidor :c',
          color: 'error'
        })
      } finally {
        this.isLoading = false
      }
    },
    async refresh () {
      try {
        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true)
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Ocurrio un error en el servidor :C',
          color: 'error'
        })
      } finally {
        this.loadingDialogMutation(false)
      }
    },
    ...mapMutations(['snackbarMutation', 'loadingTitleMutation', 'loadingDialogMutation'])
  },
  created () {
    this.getAllEventos()
  }
}
</script>
