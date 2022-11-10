<template>
    <div>
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Ordenar por:
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Crescente</a></li>
                                <li><a class="dropdown-item" href="#">Decrescente</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Classificar por por:
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Nome</a></li>
                                <li><a class="dropdown-item" href="#">Custo</a></li>
                                <li><a class="dropdown-item" href="#">Ganho</a></li>
                                <li><a class="dropdown-item" href="#">Prioridade</a></li>
                                <li><a class="dropdown-item" href="#">Prazo</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Categoria:
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Projeto</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Todas</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
        <section class="container">
            <table class="table table-light">
                <thead>
                    <tr>
                        <th>Objetivo</th>
                        <th class="text-center">Custo</th>
                        <th class="text-center">Ganho</th>
                        <th class="text-center">Prioridade</th>
                        <th class="text-center">Prazo</th>
                        <th class="text-center">Urgência</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="targets.length" v-for="target in targets" :key="target.id">
                        <th scope="row">{{ target.title }}</th>
                        <td class="text-center">{{ target.cost }}</td>
                        <td class="text-center">{{ target.gain }}</td>
                        <td class="text-center">{{ target.priority }}</td>
                        <td class="text-center">{{ target.deadline }}</td>
                        <td class="text-center">{{ target.sugested_priority }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</template>

<script>

import Cookie from 'js-cookie';

export default {
    data() {
        return {
            targets: []
        }
    },

    created() {
        this.appendTargets();
    },

    methods: {
        appendTargets(query, param, order) {
            const token = Cookie.get('access_token');

            let params = '';

            fetch(`http://localhost:8000/api/target/${params}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        this.targets = data;
                    } else {
                        alert(data.error);
                    }
                })
        }
    }
}
</script>
