<template>
<svg id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 300.07 127.13"><defs><linearGradient id="Degradado_sin_nombre_54" x1="25.76" y1="101.43" x2="29.26" y2="97.93" gradientUnits="userSpaceOnUse"><stop offset="0" stop-opacity="0.3"/><stop offset="0.99" stop-opacity="0"/></linearGradient><linearGradient id="Degradado_sin_nombre_51" x1="-4880.95" y1="-2532.98" x2="-4877.45" y2="-2536.49" gradientTransform="translate(-2422.84 4902.72) rotate(90)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-opacity="0.4"/><stop offset="0.99" stop-opacity="0"/></linearGradient><linearGradient id="Degradado_sin_nombre_54-2" x1="50.91" y1="76.27" x2="54.41" y2="72.77" xlink:href="#Degradado_sin_nombre_54"/><linearGradient id="Degradado_sin_nombre_51-2" x1="103.67" y1="65.83" x2="108.21" y2="61.29" gradientTransform="matrix(1, 0, 0, 1, 0, 0)" xlink:href="#Degradado_sin_nombre_51"/></defs>><path d="M282.62,0c9.63,0,17.44,7.14,17.44,15.94v95.23c0,8.8-7.81,15.94-17.44,15.94H17.5C7.87,127.12.06,120,.06,111.18V16C.06,7.15,7.87,0,17.5,0Z" fill="#f9ae3b"/><polygon points="101.76 127.12 51.45 127.12 28.13 103.8 0.06 75.73 0.06 25.42 53.29 78.65 101.76 127.12" :class="{'bg-active': active, 'bg-inactive': !active}"/><path d="M51.45,127.12H16A16,16,0,0,1,.06,111.18V75.73L28.13,103.8Z" :class="{'bg-active': active, 'bg-inactive': !active}"/><polygon points="58.45 127.12 51.45 127.12 28.13 103.8 0.06 75.73 0.06 68.73 58.45 127.12" fill="url(#Degradado_sin_nombre_54)"/><polygon points="88.38 50.55 84.88 47.05 103.84 28.08 131.91 0.01 138.91 0.01 88.38 50.55" fill="url(#Degradado_sin_nombre_51)"/><path d="M165,127.12H101.76L.06,25.42V16A15.94,15.94,0,0,1,16,0H37.84L38,.17,84.88,47.05Z" :class="{'bg-active': active, 'bg-inactive': !active}"/><polygon points="131.92 0.01 131.76 0.17 84.88 47.05 38 0.17 37.84 0.01 131.92 0.01" :class="{'bg-active': active, 'bg-inactive': !active}"/><polygon points="108.76 127.12 101.76 127.12 28.13 53.49 0.06 25.42 0.06 18.42 108.76 127.12" fill="url(#Degradado_sin_nombre_54-2)"/><polygon points="174.03 127.12 164.95 127.12 65.91 28.08 37.84 0.01 46.92 0.01 174.03 127.12" fill="url(#Degradado_sin_nombre_51-2)"/><circle cx="87.13" cy="63.49" r="41.5" @click="activeInteraction(index)" class="bg" :class="{'bg-active': active, 'bg-inactive': !active}"/><path d="M87.13,23.49a40,40,0,1,1-40,40,40,40,0,0,1,40-40m0-3a43,43,0,1,0,43,43,43,43,0,0,0-43-43Z" fill="#333"/></svg>
</template>

<script>
export default {
  name: 'PersonalBox',
  props: [
    'active',
    'activeInteraction',
    'index'
  ],
}
</script>

<style lang="scss" scoped>
.bg {
  cursor: pointer;
  &-active {
    fill: #09B282;
  }
  &-inactive {
    fill: #f44336;
  }
}
</style>