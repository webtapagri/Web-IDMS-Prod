create or replace view V_ROAD_CATEGORY as
select trc.id, trs.status_name, trc.category_name, trc.category_code, trc.category_initial , trc.status_id
from tm_road_category trc
join tm_road_status trs on trs.id = trc.status_id
where trc.deleted_at is null
