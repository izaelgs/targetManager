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

            let percentual = 1.06 - (interval1 / interval2);
            percentual = percentual > 0 ? percentual : 1;

            let urgency = data.priority * percentual;

            return urgency < 10 ? Math.round(urgency) : 10;
            // let urgency_log = log(urgency, 2);
            // let result = ((data.gain / data.cost) * (data.priority / 5)) / 10;
            // return round(urgency_log * result);
        }
    }
}

</script>
