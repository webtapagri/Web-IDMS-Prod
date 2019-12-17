create or replace view v_road_category as
select trs.status_name, trc.category_name, trc.category_code, trc.category_initial 
from tm_road_category trc
join tm_road_status trs on trs.id = trc.status_id
