<template>
    <transition name="slide-fade">
        <li>
            <div class="container place-item" v-if="active">
                <div class="media">
                    <figure class="media-left image">
                        <img :src="userList.img_url || listImage">
                    </figure>
                    <div class="media-content">
                        <h3 class="title has-text-primary">
                            <router-link :to="`/list/${userList.id}`">
                                {{ userList.name }}
                            </router-link>
                        </h3>
                        <p class="place-category">
                            {{ $t('user_lists_page.list_preview.places_in_list') }}
                            {{ userList.places | countPlaces }}
                        </p>
                        <p class="city-list">
                            {{ $t('user_lists_page.list_preview.cities_in_list') }}
                            <span
                                v-for="(city,index) in uniqueCities"
                                :key="index"
                                class="city-list__item"
                            >{{ city.name }}</span>
                        </p>
                    </div>
                    <div class="place-item__actions">
                        <router-link
                            class="button is-info"
                            role="button"
                            :to="`/my-lists/${userList.id}/edit`"
                        >
                            {{ $t('buttons.edit') }}
                        </router-link>

                        <DeleteModal 
                            v-if="showModal"
                            :show="showModal"
                            :user-list="userList"
                            @close="showModal = false" 
                            @preloader="$emit('loading', true)"
                        />
                        <button
                            :disabled="!!userList.is_default"
                            class="button is-danger"
                            @click="showModal = true"
                        >
                            {{ $t('buttons.delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </li>
    </transition>
</template>

<script>
import { mapGetters } from 'vuex';
import imageStub from '@/assets/no-photo.png';
import DeleteModal from './UserListDelete';

export default {
    name: 'ListPreview',
    components: {
        DeleteModal
    },
    data() {
        return {
            imageStub: imageStub,
            active: false,
            showModal: false
        };
    },
    filters: {
        countPlaces: function (places) {
            return places.length;
        },
    },
    computed: {
        ...mapGetters('userList', {
            getUniqueCities : 'getUniqueCities',
            getPlaceById: 'getPlaceById',
        }),
        uniqueCities: function () {
            return this.getUniqueCities(this.userList);
        },
        listImage() {
            if (this.userList.places.length) {
                let placeId = this.userList.places[0];
                let place = this.getPlaceById(placeId);

                return place.photos[0].img_url;
            }

            return this.imageStub;
        }
    },
    props: {
        userList: {
            required: true,
            type: Object,
        },
        timer: {
            required: true,
            type: Number,
        }
    },
    methods: {
        notLast(key) {
            return Object.keys(this.uniqueCities).length - key > 1;
        },
        like() {
            this.$toast.open({
                message: this.$t('place_page.message.review_like'),
                type: 'is-info',
                position: 'is-bottom'
            });
        },
        dislike() {
            this.$toast.open({
                message: this.$t('place_page.message.review_dislike'),
                position: 'is-bottom',
                type: 'is-info'
            });
        }
    },
    created() {
        setTimeout(() => {
            this.active = true;
        }, this.timer);
    },
    beforeDestroy() {
        this.$emit('loading', false);
    }
};
</script>

<style lang="scss" scoped>
    .place-item {
        background: #FFF;
        color: grey;
        max-width: 100%;
        margin-bottom: 1rem;
        margin-left: 0;
        padding: 10px;
        border-bottom: 1px grey solid;

        &__actions {
            display: flex;
            flex-direction: column;
            margin-top: auto;

            .button {
                margin-top: 10px;
            }
        }
    }
    .columns {
        width: 100%;
        margin: 0;
    }
    .title {
        margin-bottom: 0.5rem;
    }
    .image {
        width: 125px;
        height: 100px;
        flex-shrink: 0;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: 50% 50%;
        }
    }
    .place-category {
        margin-bottom: 0.25rem;
        a {
            color: grey;
            transition: color 0.3s;
            &:hover {
                color: black;
                text-decoration: underline;
            }
        }
    }
    .city-list {
        margin-bottom: 0.5rem;

        &__item {
            &:not(:last-child):after {
                content: ', ';
            }
        }
    }
    hr {
        color: grey;
        border-width: 3px;
    }
    .slide-fade-enter-active {
        transition: all 0.5s ease;
    }
    .slide-fade-enter, .slide-fade-leave-to {
        transform: translateX(300px);
        opacity: 0;
    }

    @media screen and (max-width: 769px) {
        .place-item {
            .media {
                flex-direction: column;
                align-items: center;
            }

            &__actions {
                flex-direction: row;

                .button {
                    margin-right: 10px;
                }
            }

            .image {
                margin-bottom: 10px;
            }
        }
    }


    @media screen and (max-width: 414px) {
        .place-item {
            font-size: .8rem;
        }

        .title {
            font-size: 1.3rem;
        }

        .image {
            width: 140px;
            height: 100px;
        }
    }
</style>