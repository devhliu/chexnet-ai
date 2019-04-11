<script>
import Chart from "chart.js";
import { HorizontalBar } from "vue-chartjs";

// make chart a bit prettier and visually appealing to the masses
Chart.defaults.global.defaultFontFamily = "Operator";
Chart.defaults.global.defaultFontSize = 16;

export default {
    extends: HorizontalBar,
    props: {
        chartdata: {
            type: Object,
            default: null
        },
        chartoptions: {
            type: Object,
            default: null
        }
    },
    mounted() {
        this.renderChart(this.chartdata, this.chartoptions);
    }
};
</script>
