<script>

export default {
    methods: {
        getUrgency(data) {

            let diffInMs1   = new Date(data.deadline) - new Date();
            let diffInDays1 = diffInMs1 / (1000 * 60 * 60 * 24);
            let interval1 = diffInDays1 > 0 ? Math.round(diffInDays1) : 1;

            let diffInMs2   = new Date(data.deadline) - new Date(data.created_at);
            let diffInDays2 = diffInMs2 / (1000 * 60 * 60 * 24);
            let interval2 = diffInDays2 > 0 ? Math.round(diffInDays2) : 0;

            let percentual = interval1 / interval2;
            percentual = percentual > 0 ? 1 - percentual : 0; // o percentual do prazo não pode ser negativo
            percentual = percentual > 1 ? percentual : percentual * 2; // se o percentual for de 100% a urgência dobra

            let urgency = data.priority * percentual;

            console.log(interval1);
            console.log(interval2);
            console.log(percentual);
            console.log('.....');

            return urgency < 10 ? Math.round(urgency) : 10; // o valor máximo de urgência é 10
        }
    }
}

</script>
