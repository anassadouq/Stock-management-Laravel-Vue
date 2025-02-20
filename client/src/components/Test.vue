<script setup>
import { ref, onMounted } from 'vue';
import jsPDF from 'jspdf';
import axios from 'axios';

const fournisseurs = ref([]);

const fetchFournisseurs = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/fournisseur');
        fournisseurs.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

onMounted(fetchFournisseurs);

const downloadPDF = () => {
    const pdf = new jsPDF();
    pdf.text('Fournisseur List', 10, 10);
    
    let y = 20;
    pdf.setFontSize(10);
    pdf.text('Nom', 10, y);
    pdf.text('Adresse', 70, y);
    pdf.text('Téléphone', 130, y);
    y += 10;
    
    fournisseurs.value.forEach(f => {
        pdf.text(f.nom, 10, y);
        pdf.text(f.adresse, 70, y);
        pdf.text(f.tel, 130, y);
        y += 10;
    });
    
    pdf.save('fournisseurs.pdf');
};
</script>

<template>
    <div>
        <table width="100%" style="text-align: center; border-collapse: collapse;" border="1">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Tel</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="fournisseur in fournisseurs" :key="fournisseur.id">
                    <td>{{ fournisseur.nom }}</td>
                    <td>{{ fournisseur.adresse }}</td>
                    <td>{{ fournisseur.tel }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="pdf">
        <button class="btn" @click="downloadPDF">Download PDF</button>
    </div>
</template>