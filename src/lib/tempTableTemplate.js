const list = [

	{
		field: 'region',
		field_num: 1,
		field_check: 'region_id',
		name: 'Субъект РФ',
		type: 'string', 
		input: 'select',
		catalog: 'region_list',
	},

	{
		field: 'last_year',
		name: 'Последний год',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'data_source',
		name: 'Источник поступления данных в ЦЗЛ',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'first_year',
		name: 'Год первого поступления данных',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'organization',
		name: 'Организация, проводившая обследования',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'lzm_registry',
		name: 'Реестр ЛЗМ',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'source_info',
		name: 'Источник информации: 1-ИИ, 2-ИИ…5-ИИ',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'lzm_registry_priority',
		name: 'Приоритет проведения ЛЗМ',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'glpm_method',
		name: 'Способ проведения  государственного лесопатологического мониторинга: 1-ГЛПМ... 7-ГЛПМ',
		type: 'string', 
		input: 'select',
	},

	{
		field: 'lzm_registry_period',
		name: 'Оптимальные сроки проведения ЛЗМ',
		type: 'string',
		input: 'select',
	},

	{
		field: 'act_num',
		name: '№ акта ЛПО',
		type: 'string', 
		input: 'select',
	},

	{
		field: 'modification',
		name: 'Внесение изменений',
		type: 'string', 
		input: 'select',
	},

	{
		field: 'month_new_modified',
		name: 'месяц для вновь выявленных или переоформленных',
		type: 'number', 
		input: 'select',
	},

	{
		field: 'registry_insertion_date',
		name: 'Дата внесения в реестр',
		type: 'date', 
		input: 'select',
	},

	{
		field: 'registry_update_date',
		name: 'Дата последнего изменеия в реестре',
		type: 'date', 
		input: 'select',
	},

	{
		field: 'forest_management_year',
		name: 'Год проведения лесоустройства',
		type: 'number', 
		input: 'select',
	},

	{
		field: 'inspection_date',
		name: 'Дата обследования',
		type: 'date', 
		input: 'select',
	},

	{
		field: 'lat',
		name: 'широта',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'lon',
		name: 'долгота',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'forestry',
		name: 'Лесничество',
		type: 'string', 
		input: 'select',
		check_field: 'forestry_id',
		catalog: 'forestry_list'
	},

	{
		field: 'localforestry',
		name: 'Участковое лесничество',
		type: 'string', 
		input: 'select',
		check_field: 'localforestry_id',
		catalog: 'localforestry_list'
	},

	{
		field: 'subforestry',
		name: 'Участок, урочище и т.п.',
		type: 'string', 
		input: 'select',
		check_field: 'subforestry_id',
		catalog: 'subforestry_list'
	},

	{
		field: 'area',
		name: 'Квартал',
		type: 'string', 
		input: 'select',
	},

	{
		field: 'section',
		name: 'Выдел',
		type: 'string', 
		input: 'select',
	},

	{
		field: 'section_lp',
		name: '№ лесопатологического выдела',
		type: 'string', 
		input: 'select',
	},

	{
		field: 's_section',
		name: 'Площадь выдела, га',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'locality_comments',
		name: 'Примечание к выделу (местоположение)',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'forest_purpose',
		name: 'Целевое назначение лесов (код)',
		type: 'string', 
		input: 'select',
	},

	{
		field: 'forest_purpose_tax',
		name: 'Целевое назначение лесов по таксационным описаниям',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'protection_category',
		name: 'Категория защитности',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'ozu',
		name: 'ОЗУ',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'oopt',
		name: 'ООПТ',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'protection_category_group_1olpm',
		name: 'Группа категория защитности',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'composition',
		name: 'Состав',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'age_group',
		name: 'Группа возраста',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'age',
		name: 'Возраст',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'bonitet',
		name: 'Бонитет',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'density',
		name: 'Полнота',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_ga',
		name: 'Запас на 1га, кбм',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'accessibility',
		name: 'Транспортная доступность',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'tenant',
		name: 'Лесопользователь',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'lease_type',
		name: 'Вид аренды',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'lease_date',
		name: 'Дата заключения договора',
		type: 'date', 
		input: 'input',
	},

	{
		field: 'lease_period',
		name: 'Срок аренды',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'inspection_year',
		name: 'год обследования',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_inspection',
		name: 'площадь обследования,га',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'permissive_letter_num',
		name: '№ разрешительного письма',
		type: 'string', 
		input: 'input',
	},

	{
		field: 's_inspection_gis',
		name: 'площадь по ДЗЗ и ЛС,га',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'som_id_old',
		name: 'Вид СОМ 2015-2019',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'som_s_old',
		name: 'площадь СОМ 2015-2019, га',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'cat_rlh',
		name: 'Категория по форме "Рослесхоз"',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'main_species',
		name: 'Главная порода',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'damaged_species',
		name: 'Повреждаемая порода',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'damage_reason_group_1olpm',
		name: 'Код группы причин',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'damage_reason',
		name: 'Причина ослабления или гибели древостоя (1-ОЛПМ)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'main_damage_reason_id_1olpm',
		name: 'Код повреждения по 1-ОЛПМ',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'secondary_damage_reason_id_1olpm',
		name: 'Код второстепенной причины повреждения по 1-ОЛПМ',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'damage_year',
		name: 'Год повреждения',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'protection_category_group_id',
		name: 'Код защитности для приложения 2, 3',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'forecast',
		name: 'Прогноз',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'damaged_species_2olpm',
		name: 'овреждаемая порода по очагам 2-ОЛПМ',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'damage_reason_group_2olpm',
		name: 'Код группы причины по 2-ОЛПМ',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'pests',
		name: 'Виды вредителей и болезней (2-ОЛПМ)',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'main_damage_reason_id_2olpm',
		name: 'Код повреждения по 2-ОЛПМ',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'pest_phase',
		name: 'Фаза развития очага',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'pest_core_number',
		name: 'Номер очага',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'common_loss',
		name: 'общий отпад',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'drying_degree',
		name: 'степень усыхания',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'current_loss',
		name: 'в т.ч. текущий',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'damaged_trees_pct_2olpm',
		name: 'поврежденные (заселенные)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'damaged_trees_degree_2olpm',
		name: 'степень повреждения (заселения)',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'main_damage_symptom_id_1olpm',
		name: 'Код признака, главный',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'main_damage_symptom_pct_1olpm',
		name: '% деревьев с наличием признака',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'secondary_damage_symptom_id_1olpm',
		name: 'Код признака второстепенный',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'secondary_damage_symptom_pct_1olpm',
		name: '% деревьев с наличием признака',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'final_density',
		name: 'Полнота, остающаяся после выборки деревьев, подлежащих рубке',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'underwood',
		name: 'Обеспечение лесовосстановления',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'species_sks',
		name: 'Средневзвешенная категория состояния породы',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'forest_sks',
		name: 'Средневзвешенная категория состояния насаждения',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'recommended_mzl_id',
		name: 'Вид рекомендации',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'felling_pct',
		name: '% деревьев от запаса подлежащий рубке',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'is_dead',
		name: 'Погибшие',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'recommended_mzl_s',
		name: 'площадь рекомендации, га',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'recommended_mzl_stock',
		name: 'запас рекомендованных,  куб. м',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'dead_year',
		name: 'год гибели',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'approved_mzl_id',
		name: 'Назначенные мероприятия текущего и прошедшего года',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'approved_mzl_s',
		name: 'Площадь МЗЛ,га',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'approved_mzl_stock',
		name: 'запас МЗЛ, куб. м',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'rad_count',
		name: 'Количество выбираемых деревьев (для РАД), шт.',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_1',
		name: 'Без признаков ослабления',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_1_r',
		name: 'Без признаков ослабления (Р)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_2',
		name: 'Ослабленные',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_2_r',
		name: 'Ослабленные (Р)и',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_3',
		name: 'Сильно ослабленные',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_3_r',
		name: 'Сильно ослабленные (Р)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_4',
		name: 'Усыхание',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_4_r',
		name: 'Усыхание (Р)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_sv_s',
		name: 'Свежий сухостой',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_sv_v',
		name: 'Свежий ветровал',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_sv_b',
		name: 'Свежий бурелом',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_st_s',
		name: 'Старый сухостой',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_st_v',
		name: 'Старый ветровал',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'sc_st_b',
		name: 'Старый бурелом',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_previous_year',
		name: 'Площадь участка на конец декабря 2020 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_01',
		name: 'площадь, га (январь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_01',
		name: 'запас, кмб. (январь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_01',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец января 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_02',
		name: 'площадь, га (февраль)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_02',
		name: 'запас, кмб. (февраль)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_02',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец февраля 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_03',
		name: 'площадь, га (март)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_03',
		name: 'запас, кмб. (март)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_03',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец марта 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_04',
		name: 'площадь, га (апрель)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_04',
		name: 'запас, кмб. (апрель)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_04',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец апреля 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_05',
		name: 'площадь, га (май)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_05',
		name: 'запас, кмб. (май)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_05',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец мая 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_06',
		name: 'площадь, га (июнь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_06',
		name: 'запас, кмб. (июнь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_06',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец июня 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_07',
		name: 'площадь,га (июль)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_07',
		name: 'запас, кмб. (июль)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_07',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец июля 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_08',
		name: 'площадь,га (август)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_08',
		name: 'запас, кмб. (август)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_08',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец августа 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_09',
		name: 'площадь,га (сентябрь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_09',
		name: 'запас, кмб. (сентябрь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_09',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец сентября 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_10',
		name: 'площадь,га (октябрь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_10',
		name: 'запас, кмб. (октябрь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_10',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец октября 2021 года',
		type: 'number', 
		input: 'input',
	},











	{
		field: 's_new_year_11',
		name: 'площадь, га (ноябрь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_11',
		name: 'запас, кмб. (ноябрь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_11',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец ноября 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_new_year_12',
		name: 'площадь, га (декабрь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'stock_new_year_12',
		name: 'запас, кмб. (декабрь)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 's_diff_12',
		name: 'Площадь выдела с наличием ослабления (усыхания) древостоя на конец декабря 2021 года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'total_mzl_id',
		name: 'Вид МЗЛ c начала года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'total_s_new_year',
		name: 'Площадь нарастающим итогом, га',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'total_stock_new_year',
		name: 'Запас МЗЛ с начала года',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'lpo_mode',
		name: 'Способ ЛПО',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'worker',
		name: 'Ф.И.О. исполнителя работ',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'user_id',
		name: 'занесено Ф.И.О.',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'data_source_1_1',
		name: 'Источники данных (для реестра приложение 1.1)',
		type: 'number', 
		input: 'input',
	},

	{
		field: 'comments',
		name: 'Примечание',
		type: 'string', 
		input: 'input',
	},

	{
		field: 'actualization_mode',
		name: 'Способ актуализации',
		type: 'string', 
		input: 'input',
	}


];

// const listJson = JSON.stringify(list);

export default list;