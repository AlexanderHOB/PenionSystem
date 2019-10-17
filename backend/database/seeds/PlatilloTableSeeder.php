<?php

use Illuminate\Database\Seeder;

class PlatilloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $uMedida = ['Kilogramos','Gramos','Onzas','Libras'];
        $area=['Área Fría','Área Caliente','Área Mixta'];
        $platillos = ['Causa Acebichada','Causa De Cangrejo','Causa De Langostinos','Causa De Pollo','Causa De Tres Sabores','Choritos A La Chorrillana','Ensalada A Lo Peñon','Ensalada De Palta Y Chonta','Ensalada Fresca','Ensalada Mixta','Leche De Pantera','Leche De Tigre Especial','Leche De Tigre Simple','Ocopa Tradicional','Papa A La Huancaína','Ronda Marina (C/S, A/M, CAUSA, LEC/S) Doncella','Ronda Caliente (CH/S, AR/MA, PIC, CAU) Doncella','Ronda Fría (C/S, TIR/S, C/CON) Doncella','Tequeño Con Pulpa de Cangrejo','Tequeños De Jamón Y Queso','Ceviche A Lo Peñon Doncella','Ceviche Clásico (C/CON, C/S) Doncella','Ceviche Con Crema De Camu Camu Doncella','Ceviche Con Crema De Cocona Doncella','Ceviche De Camarones','Ceviche De Conchas Negras','Ceviche De Dos Sabores De Doncella','Ceviche De La Selva Doncella','Ceviche De Langostinos','Ceviche De Marisco','Ceviche Mixto De Corvina','Ceviche Mixto De Corvina Con Crema De Camarones','Ceviche Mixto De Doncella','Ceviche Mixto De Doncella Con Crema De Camarones','Ceviche Mixto De Paiche','Ceviche Regional De Doncella','Ceviche Simple De Corvina','Ceviche Simple De Corvina Con Crema De Camarones','Ceviche Simple De Doncella','Ceviche Simple De Doncella Con Crema De Camarones','Ceviche Simple De Paiche','Ceviche Tricolor De Corvina','Ceviche Tricolor De Doncella','Peñoncito De Pescado Y Marisco','Chupe De Camarones','Chupe De Cangrejo','Chupe De Choros','Chupe De Pescado','Chupe Mixto','Combo I (C/S, CH/PO, LEC/S) Doncella','Combo II (C/S, CH/PO, A/M, LEC/S) Doncella','Combo III (C/S, CH/PE, A/M, LEC/S) Doncella','Combo IV (C/M, CH/S, LEC/S) Doncella','Festival De Trucha (C/S, CH/S, CHAU, LEC/S)','Arroz Chaufa De Camarones','Arroz Cahufa De Cecina','Arroz Chaufa De Langostinos','Arroz Chaufa De Marisco','Arroz Chaufa De Pescado Doncella','Arroz Chaufa De Pollo','Arroz Chaufa Regional','Arroz Con Marisco','Filete De Doncella Con Crema A Lo Peñon','Filete De Doncella Con Crema Al Ajo','Filete De Doncella Con Crema De Camarones','Milanesa De Corvina','Milanesa De Doncella','Milanesa De Pollo','Plancha Filete De Corvina','Plancha Filete De Doncella','Plancha Filete De Doncella A La Menier','Plancha Filete De Paiche','Plancha Filete De Pollo','Plancha Filete De Trucha','Arroz Con Pato','Caja China De Chancho','Caja China De Pollo','Pure Con Seco De Cabrito','Rocoto Relleno Con Pastel De Papa','Sarza De Patita De Chancho','Seco De Cabrito','Tacu - Tacu Con Lomo Saltado','Tacu - Tacu Con Salsa De Marisco','Tacu - Tacu Con Seco De Cabrito','Arroz Chaufa De Marisco Familiar','Arroz Con Marisco Familiar','Ceviche Mixto Familiar Corvina','Ceviche Mixto Familiar Doncella','Ceviche Simple Familiar Corvina','Ceviche Simple Familiar Doncella','Chicharron Mixto De Pescado Doncella','Chicharron Simple De Pescado Doncella','Combinado Familiar (C/M, A/M, CH/S, LEC/S, REF) Doncella','Jalea Mixta De Corvina Familiar','Jalea Mixta De Doncella Familiar','Cristal Vidrio','Cuzqueña Negra Vidrio','Cuzqueña Rubia Vidrio','Cuzqueña Trigo Vidrio','Pilsen Vidrio','San Juan Vidrio','7up 1 1/2 L Descartable','Coca Cola 1L Vidrio','Coca Cola Personal Vidrio','Guarana 1/2 L Descartable','Guarana 2 L Descartable','Inca Kola 1 1/2 Vidrio','Inca Kola 1/2 L Vidrio','Inca Kola 1 L Vidrio','Inca Kola Personal Vidrio','Pepsi 1 1/2 L Descartable','San Mateo Descartable sin gas','San Luis Descartable sin gas','Camu Camu Jarra 1 Lt','Camu Camu Vaso 250 Ml','Cebada Jarra 1 Lt','Cebada Vaso 250 Ml','Chicha Morada Jarra 1 Lt','Chicha Morada Vaso 250 Ml','Maracuya Jarra 1 Lt','Maracuya Vaso 250 Ml','Piña Jarra 1 Lt','Piña Vaso 250 Ml','Limonada Frozen Jarra 1 Lt','Camu Camu Jarra 1/2 Lt','Cebada Jarra 1/2 Lt','Chicha Morada Jarra 1/2 Lt','Maracuya Jarra 1/2 Lt','Piña Jarra 1/2 Lt','Budin','Crema Volteada','Flan','Gelatina','Geloflan','Helado Barquillo','Helado Copa Grande','Helado Copa Mediana','Keke Casero','Mosaico De Gelatina','Muss','Pye De Fresa','Torta 3 Leches','Torta De Chocolate','Torta Helada'];
        $codigo_pla = ['E001','E002','E003','E004','E005','E006','E007','E008','E009','E010','E011','E012','E013','E014','E015','E016','E017','E018','E019','E020','C001','C002','C003','C004','C005','C006','C007','C008','C009','C010','C011','C012','C013','C014','C015','C016','C017','C018','C019','C020','C021','C022','C023','C024','Chu1','Chu2','Chu3','Chu4','Chu5','Co01','Co02','Co03','Co04','Co05','Ar01','Ar02','Ar03','Ar04','Ar05','Ar06','Ar07','Ar08','Pm01','Pm02','Pm03','Pm04','Pm05','Pm06','Pm07','Pm08','Pm09','Pm10','Pm11','Pm12','Fs01','Fs02','Fs03','Fs04','Fs05','Fs06','Fs07','Fs08','Fs09','Fs10','Fa01','Fa02','Fa03','Fa04','Fa05','Fa06','Fa07','Fa08','Fa09','Fa10','Fa11','Cv01','Cv02','Cv03','Cv04','Cv05','Cv06','Gs01','Gs02','Gs03','Gs04','Gs05','Gs06','Gs07','Gs08','Gs09','Gs10','Au01','Au02','Rc01','Rc02','Rc03','Rc04','Rc05','Rc06','Rc07','Rc08','Rc09','Rc10','Rc11','Rc12','Rc13','Rc14','Rc15','Rc16','Ps01','Ps02','Ps03','Ps04','Ps05','Ps06','Ps07','Ps08','Ps09','Ps10','Ps11','Ps12','Ps13','Ps14','Ps15'];
        $precio = [20,15,10,8,15,15,15,10,10,10,15,15,10,8,8,28,45,45,18,15,33,28,20,20,33,25,25,25,30,30,28,32,26,28,27,22,26,28,21,25,24,35,30,18,28,27,20,20,30,18,25,30,25,35,27,18,26,22,18,18,22,23,30,20,25,20,18,18,20,18,20,20,18,20,18,20,12,20,10,10,18,22,25,24,40,40,55,50,50,43,55,45,50,45,40,8,10,8,10,7,5,15,8,2,8,15,15,8,10,2,15,2,2,10,2,10,2,10,2,10,2,10,2,12,5,5,5,5,5,2,3,2,1,3,2,5,3,2,3,3,3,3,3,3];
        for($i=1;$i<=143;$i++){

            DB::table('platillos')->insert([
                'categoria_id'   => rand(1,10),
                'nombre'        => $platillos[$i],
                'codigo'        => $codigo_pla[$i],
                'unidad_de_medida' => $uMedida[rand(0,3)],
                'area'          => $area[rand(0,2)],  
                'precio'        => $precio[$i],
                'descripcion'   => 'Descripción del platillo: '.$platillos[$i],
                'condicion'     => 1,
            ]);
        }
    }
}
