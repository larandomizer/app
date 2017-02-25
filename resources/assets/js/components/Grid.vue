<template>
    <table class="table table-condensed">
        <thead>
            <tr>
                <th :width="col.width" v-text="col.name" v-for="col in columns"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in rows" @mouseenter="rowOn(row)" @mouseleave="rowOff(row)">
                <grid-col v-for="col in columns" :id="col.key" :record="row" :current="isCurrent(row)"></grid-col>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        computed: {
            rows() {
                // loop through columns to find a single array of column keys
                let cols = this.columns.map(col => {
                    return col.key;
                });
                // Only include the data specified by the columns.
                return this.records.filter(record => {
                    return cols.indexOf(record.name !== false);
                });
            }
        },
        props: ['columns', 'records', 'current'],
        methods: {
            isCurrent(row) {
                return !! (this.current.player_id === row.player_id);
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