<template>
    <button
        class="button is-primary checkin"
        @click="checkinPlace"
        v-tooltip.top="'Check-in'"
    >
        <i class="fas fa-clock" />{{ checkinCount }}
    </button>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    name: 'PlaceCheckin',

    props: {
        place: {
            required: true,
            type: Object
        }
    },

    data() {
        return {
            checkins: 0
        };
    },

    created() {
        this.checkins = this.place.checkins;
    },

    computed: {
        checkinCount() {
            return this.checkins;
        },
    },

    methods: {
        ...mapActions('history', ['checkIn']),

        checkinPlace: function() {
            this.checkIn({
                place_id: this.place.id
            })
                .then(() => {
                    this.checkins++;
                    this.$toast.open({
                        type: 'is-success',
                        message: this.$t('messages.success.checkin')
                    });
                })
                .catch((response) => {
                    this.handleError(response);
                });
        },
    }
};
</script>

<style lang="scss" scoped>
    .checkin {
        border-radius: 7px;
        height: 48px;
        font-size: 1.1rem;
        color: #FFF;
        text-align: center;
        padding: 0 7px;

        i {
            padding-right: 5px;
        }
    }
</style>
