<template>
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th v-text="col" v-for="col in columns"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows" @mouseenter="rowOn(row)" @mouseleave="rowOff(row)">
                    <grid-col v-for="col in cols" :id="col" :record="row" :active="isActive(row)"></grid-col>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        computed: {
            cols() {
                return _.keys(this.columns);
            },
            rows() {
                // Only include the data specified by the columns.
                return this.records.filter(record => {
                    return this.cols.indexOf(record.name !== false);
                });
            }
        },
        props: ['columns', 'records', 'connection'],
        methods: {
            isActive(row) {
                return !! (this.connection.uuid === row.uuid);
            },
            rowOn(row) {
                return Event.fire('grid.row.on', row);
            },
            rowOff(row) {
                return Event.fire('grid.row.off', row);
            }
        }
    }
</script>
